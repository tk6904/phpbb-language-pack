<?php
/**
 * 拡張機能（ext/）の言語ファイル検証
 *
 *   php tools/ext_verify.php <en-dir> <ja-dir>
 *
 * 例（公式フルパッケージを展開済みの場合）:
 *   php tools/ext_verify.php \
 *     "/path/to/phpBB3/ext/phpbb/viglink/language/en" \
 *     3.3.18/ext/phpbb/viglink/language/ja
 *
 * ext の en はリポジトリに持たない（公式パッケージから都度参照する）ため、
 * 本体用の lang_verify.php とは別スクリプトにしてある。
 * 終了コード: 0=PASS / 1=FAIL
 */
define('IN_PHPBB', true);

$EN = isset($argv[1]) ? rtrim($argv[1], '/') : '';
$JA = isset($argv[2]) ? rtrim($argv[2], '/') : '';
if (!is_dir($EN) || !is_dir($JA)) {
	fwrite(STDERR, "usage: php tools/ext_verify.php <en-dir> <ja-dir>\n");
	exit(2);
}

$fail = 0; $warn = 0;
function load($f) { $lang = array(); if (!file_exists($f)) { return null; } @include $f; return is_array($lang) ? $lang : array(); }
function ph($s) { preg_match_all('/%\d+\$[sd]|%[sd]/', $s, $m); $r = $m[0]; sort($r); return $r; }
function tags($s) { preg_match_all('#<[^>]+>#', $s, $m); return $m[0]; }
function hrefs($s) { preg_match_all('#href="([^"]+)"#', $s, $m); return $m[1]; }

foreach (glob("$EN/*.php") as $enf) {
	$base = basename($enf);
	$jaf = "$JA/$base";
	echo "### $base\n";
	if (!file_exists($jaf)) { echo "  FAIL  ja に存在しない\n\n"; $fail++; continue; }

	exec('php -l ' . escapeshellarg($jaf) . ' 2>&1', $o, $rc);
	if ($rc !== 0) { echo "  FAIL  php -l が通らない\n"; $fail++; }

	$e = load($enf); $j = load($jaf);
	$oe = array_diff(array_keys($e), array_keys($j));
	$oj = array_diff(array_keys($j), array_keys($e));
	if ($oe) { echo "  FAIL  ja に欠落: " . implode(', ', $oe) . "\n"; $fail++; }
	if ($oj) { echo "  FAIL  ja に余分: " . implode(', ', $oj) . "\n"; $fail++; }
	if (!$oe && !$oj) { echo "  ok    キー集合一致 (" . count($e) . " キー)\n"; }

	foreach ($e as $k => $v) {
		if (!isset($j[$k]) || !is_string($v) || !is_string($j[$k])) { continue; }
		if (ph($v) !== ph($j[$k])) {
			echo "  FAIL  $k placeholder  en=[" . implode(',', ph($v)) . "] ja=[" . implode(',', ph($j[$k])) . "]\n"; $fail++;
		}
		if (hrefs($v) !== hrefs($j[$k])) {
			echo "  FAIL  $k href  en=" . count(hrefs($v)) . " 件 ja=" . count(hrefs($j[$k])) . " 件\n"; $fail++;
		}
		if (tags($v) !== tags($j[$k])) {
			echo "  WARN  $k タグ列  en=" . count(tags($v)) . " 件 ja=" . count(tags($j[$k])) . " 件\n"; $warn++;
		}
		// 英語原文がそのまま訳文に残っていないか
		if (strlen($v) > 40 && $v !== $j[$k] && strpos($j[$k], substr($v, 0, 40)) !== false) {
			echo "  FAIL  $k 英語原文が訳文に混入\n"; $fail++;
		}
	}
	echo "\n";
}
echo $fail === 0 ? "RESULT: PASS (warn=$warn)\n" : "RESULT: FAIL ($fail 件, warn=$warn)\n";
exit($fail === 0 ? 0 : 1);
