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
- 末尾改行は 1 つだけ（空行を追加しない）

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

## 翻訳ベース

`language/ja/` は phpBB 3.3.4 公式日本語パック（imagina, ocean=Yohsuke, hamasaki_takeshi, Takefumi Tenshima）をベースに、3.3.17 までの差分を反映している。`iso.txt` の 3 行目はこの履歴に従い 4 名を併記している。

## 検証スクリプト

`/tmp/.../scratchpad/` に検証用の補助 PHP スクリプトを置いていたが、セッションを跨ぐと消える。必要なら同じ仕様で書き直すこと：

- `lang_verify.php` — en と ja の `$lang` キー集合一致と `php -l` 一括検証
- `text_drift.php` — 旧バージョン en と新バージョン en の値差分（既存 ja 訳の陳腐化検出）
- `email_drift.php` — `email/*.txt` のバージョン間差分
- `validate_all.php` — placeholder / HTML タグ / 配列形状の網羅チェック（誤検知に注意：複数形 `[2]` 欠落や `<p>` 不整合は phpBB 公式仕様）
