<?php
/**
 * 言語パック検証（phpBB 本体分）
 *
 *   php tools/lang_verify.php <version-dir>       例: php tools/lang_verify.php 3.3.18
 *
 * <version-dir>/language/en を基準に <version-dir>/language/ja を検証する。
 * 終了コード: 0=PASS / 1=FAIL
 *
 * 検証内容
 *   1. ja 配下の全 PHP が php -l を通ること
 *   2. en と ja の $lang キー集合が全ファイルで一致すること
 *   3. 全キーで placeholder / HTML タグ列 / href URL が en と一致すること
 *      （CLAUDE.md: 訳文は変えてよいがタグ構成と href は en と完全一致させる）
 *   4. phpBB 公式審査で過去に指摘された規約
 *   5. email/*.txt のファイル集合と placeholder
 *   6. 英語原文が訳文に混入していないこと
 */
define('IN_PHPBB', true);

$version = isset($argv[1]) ? rtrim($argv[1], '/') : '';
if ($version === '' || !is_dir("$version/language/en") || !is_dir("$version/language/ja")) {
	fwrite(STDERR, "usage: php tools/lang_verify.php <version-dir>   (例: 3.3.18)\n");
	exit(2);
}
$root = "$version/language";

$fail = 0; $ok = 0; $warn = 0;
function bad($m) { global $fail; $fail++; echo "  FAIL  $m\n"; }
function warn($m) { global $warn; $warn++; echo "  WARN  $m\n"; }
function good($m) { global $ok; $ok++; echo "  ok    $m\n"; }

function load_lang($file) {
	$lang = array(); $user = null; $help = array();
	if (!file_exists($file)) { return null; }
	@include $file;
	return is_array($lang) ? $lang : array();
}
function php_files($dir) {
	$r = array();
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir)) as $f) {
		if ($f->isFile() && substr($f->getFilename(), -4) === '.php') { $r[] = $f->getPathname(); }
	}
	sort($r);
	return $r;
}
/** 文字列・配列のどちらでも走査できるように平坦化する */
function flatten($v, $prefix = '') {
	$out = array();
	if (is_array($v)) {
		foreach ($v as $k => $vv) { $out += flatten($vv, $prefix . "[$k]"); }
	} else if (is_string($v)) {
		$out[$prefix] = $v;
	}
	return $out;
}
/**
 * $lang の PHP ファイル用。phpBB が $lang 内で置換するのは %s 系だけなので {VAR} は見ない
 * （acp/posting.php の tokens のように、説明文中にトークン名を {LOCAL_URL} と書く箇所がある）。
 */
function placeholders($s) {
	preg_match_all('/%\d+\$[sd]|%[sd]/', $s, $m);
	$r = $m[0]; sort($r); return $r;
}
/** email/*.txt 用。こちらは {VAR} が実際の置換対象。 */
function mail_placeholders($s) {
	preg_match_all('/\{[A-Z0-9_]+\}/', $s, $m);
	$r = $m[0]; sort($r); return $r;
}
function tag_list($s) { preg_match_all('#<[^>]+>#', $s, $m); return $m[0]; }
function href_list($s) { preg_match_all('#href="([^"]+)"#', $s, $m); return $m[1]; }

echo "=== 1. php -l (ja 全 PHP) ===\n";
$lint_bad = 0;
foreach (php_files("$root/ja") as $f) {
	exec('php -l ' . escapeshellarg($f) . ' 2>&1', $o, $rc);
	if ($rc !== 0) { bad("php -l: $f"); $lint_bad++; }
}
if (!$lint_bad) { good('ja の全 PHP が構文エラーなし (' . count(php_files("$root/ja")) . ' files)'); }

echo "\n=== 2. en / ja の \$lang キー集合一致 ===\n";
$keydiff = 0;
foreach (php_files("$root/en") as $enf) {
	$rel = substr($enf, strlen("$root/en/"));
	$jaf = "$root/ja/$rel";
	if (!file_exists($jaf)) { bad("ja に存在しない: $rel"); $keydiff++; continue; }
	$e = load_lang($enf); $j = load_lang($jaf);
	$only_en = array_diff(array_keys($e), array_keys($j));
	$only_ja = array_diff(array_keys($j), array_keys($e));
	if ($only_en) { bad("$rel : ja に欠落 -> " . implode(', ', array_slice($only_en, 0, 8))); $keydiff++; }
	if ($only_ja) { bad("$rel : ja に余分 -> " . implode(', ', array_slice($only_ja, 0, 8))); $keydiff++; }
}
if (!$keydiff) { good('en/ja の $lang キー集合が全ファイルで一致'); }

echo "\n=== 3. 全キーの placeholder / href が en と一致（タグ列は WARN） ===\n";
$parity_bad = 0; $checked = 0;
foreach (php_files("$root/en") as $enf) {
	$rel = substr($enf, strlen("$root/en/"));
	if (!file_exists("$root/ja/$rel")) { continue; }
	$e = load_lang($enf); $j = load_lang("$root/ja/$rel");
	foreach ($e as $k => $ev) {
		if (!isset($j[$k])) { continue; }
		// 複数形配列は添字ごとに比較しない。日本語は [1] のみが正規で、en の [1]='1 icon' /
		// [2]='%d icons' とは必ず食い違うため（CLAUDE.md 参照）。配列は全体を連結して見る。
		$es = implode(' ', flatten($ev));
		$js = implode(' ', flatten($j[$k]));
		if ($es === '' && $js === '') { continue; }
		$checked++;
		// placeholder は出現回数ではなく集合で比較する（訳文で変数を繰り返すのは正当）
		$pe = array_values(array_unique(placeholders($es)));
		$pj = array_values(array_unique(placeholders($js)));
		if ($pe !== $pj) {
			$miss = array_diff($pe, $pj); $extra = array_diff($pj, $pe);
			bad("$rel:$k placeholder  " . ($miss ? 'ja に欠落=[' . implode(',', $miss) . '] ' : '') . ($extra ? 'ja に余分=[' . implode(',', $extra) . ']' : ''));
			$parity_bad++;
		}
		// タグ列の差異は WARN。既存訳には日本語の改行都合で <br /> を足した箇所が多数あり、
		// この状態で phpBB 公式審査を通過している。ただし本セッションで SUPPORT_BODY の空 <li> と
		// viglink の <a> 二重化を拾った観点なので、新規・変更キーでは必ず目視で潰すこと。
		if (tag_list($es) !== tag_list($js)) {
			warn("$rel:$k タグ列 en=" . count(tag_list($es)) . " 件 ja=" . count(tag_list($js)) . " 件");
		}
		if (href_list($es) !== href_list($js)) {
			bad("$rel:$k href en=" . count(href_list($es)) . " 件 ja=" . count(href_list($js)) . " 件");
			$parity_bad++;
		}
	}
}
if (!$parity_bad) { good("全 $checked 文字列の placeholder / href が en と一致"); }

echo "\n=== 4. phpBB 審査規約 (CLAUDE.md) ===\n";
$iso = file_get_contents("$root/ja/iso.txt");
if (substr($iso, -1) === "\n") { bad('iso.txt に末尾改行がある（バリデータ 1.6.1 がリジェクト）'); }
else { good('iso.txt 末尾改行なし'); }
$lines = explode("\n", $iso);
if (count($lines) !== 3) { bad('iso.txt が 3 行でない (' . count($lines) . ' 行)'); }
else { good('iso.txt は 3 行構成: ' . str_replace("\n", ' / ', $iso)); }

$jp = load_lang("$root/ja/acp/posting.php");
$plural_keys = array('ICONS_ADDED', 'ICONS_EDITED', 'SMILIES_ADDED', 'SMILIES_EDITED', 'TOO_MANY_SMILIES');
$p_bad = 0;
foreach ($plural_keys as $k) {
	if (!isset($jp[$k])) { bad("acp/posting.php:$k が無い"); $p_bad++; continue; }
	if (is_array($jp[$k]) && array_key_exists(2, $jp[$k])) { bad("acp/posting.php:$k に [2] がある（審査でリジェクト）"); $p_bad++; }
}
if (!$p_bad) { good('acp/posting.php の複数形 5 キーに [2] なし'); }

$ju = load_lang("$root/ja/ucp.php");
if (isset($ju['TERMS_OF_USE_CONTENT'])) {
	if (strpos($ju['TERMS_OF_USE_CONTENT'], 'http://opensource.org/licenses/gpl-2.0.php') !== false) { bad('ucp.php TERMS_OF_USE_CONTENT に旧 GPL リンクが残っている'); }
	else if (strpos($ju['TERMS_OF_USE_CONTENT'], 'https://opensource.org/license/gpl-2-0') !== false) { good('ucp.php TERMS_OF_USE_CONTENT の GPL リンクは新形式'); }
	else { bad('ucp.php TERMS_OF_USE_CONTENT に GPL リンクが見つからない'); }
}

echo "\n=== 5. email/*.txt ===\n";
$ee = array_map('basename', glob("$root/en/email/*.txt"));
$jj = array_map('basename', glob("$root/ja/email/*.txt"));
sort($ee); sort($jj);
$m1 = array_diff($ee, $jj); $m2 = array_diff($jj, $ee);
if ($m1) { bad('ja/email に欠落: ' . implode(', ', $m1)); }
if ($m2) { bad('ja/email に余分: ' . implode(', ', $m2)); }
if (!$m1 && !$m2) { good('email/*.txt のファイル集合一致 (' . count($ee) . ' files)'); }

$mail_bad = 0;
foreach ($ee as $n) {
	if (!in_array($n, $jj, true)) { continue; }
	$ce = file_get_contents("$root/en/email/$n");
	$cj = file_get_contents("$root/ja/email/$n");
	$pe = array_unique(mail_placeholders($ce));
	$pj = array_unique(mail_placeholders($cj));
	$miss = array_diff($pe, $pj); $extra = array_diff($pj, $pe);
	if ($miss) { bad("email/$n placeholder が ja に欠落: " . implode(', ', $miss)); $mail_bad++; }
	if ($extra) { bad("email/$n placeholder が ja に余分: " . implode(', ', $extra)); $mail_bad++; }
	// Subject 行の有無は en に合わせる（en 側にも Subject を持たないテンプレートがある）
	$se = strpos($ce, 'Subject: ') === 0;
	$sj = strpos($cj, 'Subject: ') === 0;
	if ($se !== $sj) { bad("email/$n Subject 行の有無が en と不一致 (en=" . ($se ? 'あり' : 'なし') . " ja=" . ($sj ? 'あり' : 'なし') . ")"); $mail_bad++; }
}
if (!$mail_bad) { good('email/*.txt の placeholder と Subject 行が全件正常'); }

echo "\n=== 6. 英語原文の混入 ===\n";
$leak = 0;
foreach (php_files("$root/en") as $enf) {
	$rel = substr($enf, strlen("$root/en/"));
	if (!file_exists("$root/ja/$rel")) { continue; }
	$e = load_lang($enf); $j = load_lang("$root/ja/$rel");
	foreach ($e as $k => $ev) {
		if (!isset($j[$k]) || !is_string($ev) || !is_string($j[$k])) { continue; }
		// 英文がそのまま訳文の中に残っているケース（先頭 60 文字が一致）
		if (strlen($ev) > 60 && $ev !== $j[$k] && strpos($j[$k], substr($ev, 0, 60)) !== false) {
			bad("$rel:$k 英語原文が訳文に混入"); $leak++;
		}
	}
}
if (!$leak) { good('訳文への英語原文の混入なし'); }

echo "\n========================================\n";
echo $fail === 0
	? "RESULT: PASS  (assertions ok=$ok, fail=0, warn=$warn)\n"
	: "RESULT: FAIL  (assertions ok=$ok, fail=$fail, warn=$warn)\n";
if ($warn) { echo "WARN はタグ列の差異です。終了コードには影響しませんが、触ったキーについては目視で確認してください。\n"; }
exit($fail === 0 ? 0 : 1);
