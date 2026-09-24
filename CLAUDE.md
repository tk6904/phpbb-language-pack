# CLAUDE.md

このリポジトリは phpBB 日本語言語パックのソースです。Claude Code は新しいセッションでもこのファイルを読み込むので、過去の審査で得られた知見はここに残します。

## 日本語言語パック規約（phpBB 公式審査での指摘ベース）

### iso.txt は 3 行構成のみ

```
Japanese
日本語
<authors>
```

- 4 行目に `ltr` を書かない（DIRECTION は `language/ja/common.php` の `$lang['DIRECTION']` 側で指定する）
- **末尾改行を入れない**（3 行目の著者名の直後でファイルが終わる。末尾に `\n` があるとバリデータ 1.6.1 は「空の 4 行目」と数えてリジェクトする。2 回目の審査で実際にリジェクトされた）

### `acp/posting.php` の以下のキーには `[2]` を**追加しない**

```
ICONS_ADDED
ICONS_EDITED
SMILIES_ADDED
SMILIES_EDITED
TOO_MANY_SMILIES
```

日本語は `[1]` のみ（必要なら `[0]` も）が正規。第三者の検証ツールは `[2]` 欠落を warning にすることがあるが、**phpBB 公式審査では `[2]` を持たせるとリジェクトされる**。誤検知に合わせて埋めないこと。

phpBB の Japanese plural rule は count に関わらず `1` を返すので、`[2]` を埋めても実行時には参照されず、配布物としては不要な重複。

### URL は en の最新版に合わせる

`ucp.php` の `TERMS_OF_USE_CONTENT` の GPL リンクは `https://opensource.org/license/gpl-2-0`（新形式）。旧リンク `http://opensource.org/licenses/gpl-2.0.php` のままだと審査で「additional html」エラーになる（2 回目の審査で実際に指摘された）。長文の法的文言でも、**訳文を維持したまま `<a href>` の URL・タグ構成だけは en と完全一致させる**こと。

## ディレクトリ構成

```
3.3.17/
├── language/ja/        ← phpBB 本体用の翻訳（96 ファイル + iso.txt + LICENSE）
├── ext/<vendor>/<ext>/language/ja/   ← 拡張機能の翻訳
├── styles/<style>/theme/ja/           ← スタイル theme の言語別アセット
├── japanese_3_3_17.zip                ← 配布アーカイブ（language/ja, ext, styles を同梱）
└── _backup/            ← 大きな書き換え前の作業バックアップ
```

`language/en/` は翻訳作業の参照用で、配布 ZIP には含めない。

### 配布 ZIP に含めるもの

配布 ZIP には**公式フルパッケージに対応物が存在するものだけ**を同梱する。現状の対象は 3 つ。

| ZIP 内 | 公式パッケージ側の対応 |
|---|---|
| `language/ja/` | `language/en/` |
| `ext/phpbb/viglink/language/ja/` | `ext/phpbb/viglink/` |
| `styles/prosilver/theme/ja/` | `styles/prosilver/theme/en/` |

**`phpbb/webpushnotifications` は含めない。リポジトリにも置かない（3.3.18 以降）。**

phpBB 本体のフルパッケージに同梱されない別配布の拡張のため。3.3.18 の公式フルパッケージ（`PHPBB_VERSION = '3.3.18'`、`phpbb/` 875 ファイルの完全版）を全文検索して、**ファイル名・ファイル内容とも 0 ヒット**であることを実測で確認済み。3.3.17 の配布 ZIP も従来からこの拡張を除外していた。

3.3.17 側の `ext/phpbb/webpushnotifications/language/ja/` は履歴として温存してあるので、訳文が必要になったらそこから拾える。

## 新バージョンのリリース手順

マイナーリリースごとに、気づいた不具合もあわせて直していく方針。

### 1. ベースは「直前の完成版」

`3.3.19/` は **`3.3.18/` をコピーして作る**。`3.3.17/` はリリース済みの履歴として凍結してあり、[既知の FAIL](#既知の-fail) 6 件がそのまま残っているので、ここをベースにしてはいけない。

### 2. 上流の言語差分を `language/en/` に当てる

phpBB 側が公開する差分（3.3.18 のときは [gist](https://gist.github.com/marc1706/7853fe3ffc8dba51e4fcb8a276f17038)）を `language/en/` に適用し、新規・変更されたキーだけを `language/ja/` で訳す。

en 側は gist を `patch -p2` で当てたあと、**公式フルパッケージの `language/en/` と `diff -r` で完全一致すること**を確かめ、以後はパッケージ側をコピーして使う。3.3.19 のように「直前版→新版」と「2 つ前→新版」の gist が両方届いたときは、それぞれを対応する旧 en に当てて結果が一致するかも見る（3.3.19 では一致した）。

キーが削除されたら ja からも消す（残すと `lang_verify.php` がキー集合で FAIL にする）。**文面だけが変わったキーは検証スクリプトでは拾えない**ので、diff の `-`/`+` 行を 1 件ずつ見て訳し直すこと。

### 3. 公式フルパッケージの中身を必ず確認する

**差分だけを信用しない。** 公式フルパッケージをダウンロードして展開し、実物と突き合わせること。

3.3.18 では `ext/phpbb/viglink/language/en/viglink_common.php` が新規追加されていたが、**この gist の言語差分には載っていなかった**。フルパッケージを開いて初めて気づいたもので、差分だけ追っていたら 1 ファイル欠けたまま出荷していた。

確認する点:

```bash
# バージョンが期待どおりか
grep PHPBB_VERSION <pkg>/includes/constants.php

# ext/ に何が同梱されているか（増減を見る）
ls <pkg>/ext/phpbb/

# ext の言語ファイルが増えていないか
find <pkg>/ext -path '*/language/en/*' -name '*.php'

# styles の言語別アセット
ls -d <pkg>/styles/prosilver/theme/*/
```

`ext/` の en はリポジトリに持たないので、この展開済みパッケージが `ext_verify.php` の比較元になる。

### 4. 検証を通す

```bash
php tools/lang_verify.php 3.3.19
php tools/ext_verify.php "<pkg>/ext/phpbb/viglink/language/en" 3.3.19/ext/phpbb/viglink/language/ja
```

`lang_verify.php` は **FAIL 0 件**にする。WARN は既存訳由来のものが 70 件前後あるので全部は潰さなくてよいが、**自分が新規追加・変更したキーの WARN は必ず潰す**。

### 5. 配布 ZIP を再生成する

[配布 ZIP に含めるもの](#配布-zip-に含めるもの)の 3 パスのみ。生成後、ZIP の中身が作業ツリーとバイト一致することと、前バージョンの ZIP との構成差分が意図どおりかを確認する。

`zip -r language/ja ext/phpbb/viglink/language/ja ...` だけだと `ext/`・`ext/phpbb/` などの**親ディレクトリのエントリが入らない**（3.3.18 の ZIP には入っている）。先に親ディレクトリを非再帰で `zip` してから本体を追加し、`unzip -Z1` のエントリ一覧を前版と `diff` して揃える。

### 6. 気づいた不具合を直す

検証で出た FAIL は、上流差分と無関係な既存不具合でもそのリリースで直す。直したら CLAUDE.md の「既知の FAIL」表を更新する。

## 翻訳ベース

`language/ja/` は phpBB 3.3.4 公式日本語パック（imagina, ocean=Yohsuke, hamasaki_takeshi, Takefumi Tenshima）をベースに、3.3.17 までの差分を反映している。`iso.txt` の 3 行目はこの履歴に従い 4 名を併記している。

## 検証スクリプト

`tools/` に置いてある。以前は scratchpad に置いてセッションごとに書き直していたが、実際に消えて書き直す事故が起きたのでリポジトリ入りにした。**書き直さずにこれを使うこと。**

```bash
php tools/lang_verify.php 3.3.18          # phpBB 本体分（language/en を基準に language/ja を検証）
php tools/ext_verify.php <en-dir> <ja-dir>  # 拡張機能分
```

`ext/` の en はリポジトリに持たない（公式フルパッケージから都度参照する）ため、本体用とは別スクリプトにしてある。

```bash
php tools/ext_verify.php \
  "/path/to/phpBB3/ext/phpbb/viglink/language/en" \
  3.3.18/ext/phpbb/viglink/language/ja
```

### FAIL と WARN の区別

**FAIL（終了コード 1）** — 放置しない。

- `php -l` / `$lang` キー集合 / placeholder 集合 / href の並び
- `iso.txt` の行数と末尾改行、`acp/posting.php` の `[2]`、`ucp.php` の GPL リンク形式
- `email/*.txt` のファイル集合・placeholder・`Subject:` 行の有無
- 訳文への英語原文の混入

**WARN（終了コードに影響しない）** — HTML タグ列の件数差。

既存訳には日本語の改行都合で `<br />` を足した箇所が 75 件あり、**この状態で phpBB 公式審査を通過している**ため一律 FAIL にはしない。ただし SUPPORT_BODY の空 `<li>`（3.3.18 で修正）と viglink の `<a>` 二重化（同）を拾ったのはこの観点なので、**新規・変更したキーについては WARN も必ず潰すこと**。

### 誤検知を作り込まないための注意

過去に実際に踏んだもの。同じ罠を再実装しないこと。

- **複数形配列を添字ごとに比較しない。** 日本語は `[1]` のみが正規なので、en の `[1]='1 icon'` / `[2]='%d icons'` とは必ず食い違う。配列は全体を連結してから比較する。
- **placeholder は出現回数ではなく集合で比較する。** 訳文で同じ変数を繰り返すのは正当。
- **`$lang` の PHP ファイルで `{VAR}` を placeholder 扱いしない。** phpBB が `$lang` 内で置換するのは `%s` 系だけ。`{VAR}` が実際の置換対象なのは `email/*.txt` のみ。`acp/posting.php` の `tokens` は説明文中に `{LOCAL_URL}` と書いている。
- **`Subject:` 行の有無は en と照合する。** en 側にも `Subject:` を持たないテンプレートがある（`admin_send_email.txt` など）。

### 既知の FAIL

**3.3.18・3.3.19 は FAIL 0 件（PASS）。** バージョンディレクトリを追加したら、まず `lang_verify.php` を流して PASS を確認してから作業を始めること。

3.3.17 には以下の 6 件が残っている。リリース済みの履歴として手を付けていない。3.3.18 では修正済みなので、3.3.17 を新バージョンのベースにする場合は取り込むこと。

| 箇所 | 内容 |
|---|---|
| `ucp.php:FIELD_TOO_SMALL` / `FIELD_TOO_LARGE` | placeholder の型と順序が en と逆（`%1$d`/`%2$s` ↔ `%1$s`/`%2$d`）。phpBB は `(最小値, フィールド名)` の順で渡すので、**フィールド名の位置に数値が出て閾値の位置に `0` が出る** |
| `acp/board.php:ACP_COOKIE_SETTINGS_EXPLAIN` | en 末尾の phpBB.com ナレッジベースへのリンクが訳から脱落 |
| `help/bbcode.php:HELP_BBCODE_LINKS_BASIC_ANSWER` | 2 本のリンクが `http://`（en は `https://`） |
| `help/faq.php:HELP_FAQ_ISSUES_WHOIS_PHPBB_ANSWER` | `https://www.phpbb.com/about/` へのリンクが脱落。「phpBB Group」表記も en の「phpBB Limited」と不一致 |
| `install.php:UPDATE_INSTRUCTIONS` | 訳が古い版のままで、「フルパッケージでアップデートする方法」の章が丸ごと欠落（推奨手順）。en の 4 本のリンクのうち 2 本が脱落するのはこれが原因 |

### 長い HTML を訳し直すときは en を雛形にする

`UPDATE_INSTRUCTIONS` のように markup の多い値は、訳文を一から書かずに **en の値をコピーしてテキストノードだけ差し替える**。タグの数・順序・属性が自動的に一致する。

それでも順序は崩し得る。3.3.18 の修正時、「パッケージ側から削除する」の文で `<em>` を `<code>` 群より前に動かしてしまい、**タグ件数は 95 対 95 で一致したまま順序だけ違う**状態を作った。`lang_verify.php` の WARN は件数ではなく並びを見ているので拾えた。件数一致を根拠に安心しないこと。

### 未実装（必要になったら書く）

- `text_drift.php` — 旧バージョン en と新バージョン en の値差分（既存 ja 訳の陳腐化検出）
- `email_drift.php` — `email/*.txt` のバージョン間差分
