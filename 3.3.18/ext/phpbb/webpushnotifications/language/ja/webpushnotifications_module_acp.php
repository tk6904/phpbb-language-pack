<?php
/**
 *
 * phpBB Browser Push Notifications. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2023, phpBB Limited <https://www.phpbb.com>
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * DO NOT CHANGE
 */
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = [];
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, [
	// Web push settings
	'ACP_WEBPUSH_SETTINGS_EXPLAIN'	=> 'ここでは、掲示板の通知にWeb Pushを使用する設定を有効にできます。Web Pushは、ユーザーエージェントにイベント（一般にプッシュメッセージと呼ばれます）をリアルタイムで配信するためのプロトコルです。デスクトップとモバイルデバイスの最新ブラウザーの大半に対応しています。ユーザーは、UCPで購読し、受け取りたい通知を有効にすることで、ブラウザーでWeb Push通知を受け取ることを選択できます。<br><br>Appleのモバイルデバイスでプッシュ通知を使用するには、サイトがプログレッシブウェブアプリ（PWA）として機能する必要があります。Appleでは、プッシュ通知を有効にする前に、ユーザーがサイトをデバイスのホーム画面に追加する必要があります。アプリの外観は<strong>PWA設定</strong>で設定してください。',
	'WEBPUSH_ENABLE'				=> 'Web Pushを有効にする',
	'WEBPUSH_ENABLE_EXPLAIN'		=> 'ユーザーがWeb Pushを通じてブラウザーまたはデバイスで通知を受け取れるようにします。Web Pushを利用するには、有効なVAPID識別キーを入力または生成する必要があります。',
	'WEBPUSH_GENERATE_VAPID_KEYS'	=> '識別キーを生成',
	'WEBPUSH_VAPID_PUBLIC'			=> 'サーバー識別用公開キー',
	'WEBPUSH_VAPID_PUBLIC_EXPLAIN'	=> 'Voluntary Application Server Identification（VAPID）公開キーは、サイトからのプッシュメッセージを認証するために共有されます。<br><em><strong>注意：</strong>VAPID公開キーを変更すると、すべてのWeb Push購読が自動的に無効になります。</em>',
	'WEBPUSH_VAPID_PRIVATE'			=> 'サーバー識別用秘密キー',
	'WEBPUSH_VAPID_PRIVATE_EXPLAIN'	=> 'Voluntary Application Server Identification（VAPID）秘密キーは、サイトから配信する認証済みのプッシュメッセージを生成するために使用されます。VAPID秘密キーは、VAPID公開キーと組み合わせて有効な公開キーと秘密キーのペアを<strong>必ず</strong>形成する必要があります。<br><em><strong>注意：</strong>VAPID秘密キーを変更すると、すべてのWeb Push購読が自動的に無効になります。</em>',
	'WEBPUSH_METHOD_ENABLED'		=> 'ユーザー単位のWeb Push通知をデフォルトで有効にする',
	'WEBPUSH_METHOD_ENABLED_EXPLAIN'=> 'この設定を有効にすると、ブラウザーの通知を有効にして許可しているユーザーは、自動的に通知を受け取り始めます。不要な通知は、UCPの通知設定で無効にできます。<br><br>この設定を無効にすると、ユーザーがプッシュ通知を有効にしていても、UCPの通知設定で受け取りたい個別の通知を許可するまで、通知は届きません。',
	'WEBPUSH_DROPDOWN_SUBSCRIBE'	=> '通知ドロップダウンにWeb Push設定を表示',
	'WEBPUSH_DROPDOWN_SUBSCRIBE_EXPLAIN'=> '通知ドロップダウンに「Web Pushを有効にする」切り替えスイッチを表示するかどうかを設定します。これにより、フォーラムのどのページからでも、ユーザーがプッシュ通知を簡単に有効または無効にできます。',
	'WEBPUSH_POPUP_PROMPT'			=> '購読していないメンバーにポップアッププロンプトを表示',
	'WEBPUSH_POPUP_PROMPT_EXPLAIN'	=> '登録メンバーにプッシュ通知を受け取るかどうかを尋ねるポップアップメッセージを表示します。このポップアップは、現在購読しておらず、以前に拒否していないメンバーにのみ表示されます。',
	'WEBPUSH_INSECURE_SERVER_ERROR' => 'この掲示板では、安全なSSL/HTTPSプロトコルが使用されていません。これはWeb Push通知を有効にするために必要です。あるいは、サーバー環境が正しく設定されていない可能性があります。サーバー環境変数<em>HTTPS</em>および<em>HEADER_CLIENT_PROTO</em>が正しく設定されていることを確認してください。',

	// PWA Settings
	'ACP_PWA_SETTINGS_EXPLAIN'	=> 'プログレッシブウェブアプリ（PWA）を使用すると、ユーザーは掲示板をモバイルデバイスにインストールし、アプリのように利用できます。これらの設定では、ユーザーに表示されるアプリ名、アイコン、色、インストールプロンプトを制御します。',
	'PWA_SHORT_NAME'			=> 'サイトの短縮名',
	'PWA_SHORT_NAME_EXPLAIN'	=> '12文字以内のサイト名です。モバイルデバイスのホーム画面に表示されるアイコンのラベルとして使用される場合があります。（このフィールドを空欄にすると、<samp>サイト名</samp>の先頭12文字が使用されます。）',
	'PWA_SHORT_NAME_INVALID'	=> '「サイトの短縮名」が12文字の制限を超えています。',
	'PWA_ICON_SMALL'			=> 'モバイルデバイス用小アイコン',
	'PWA_ICON_SMALL_EXPLAIN'	=> '192px x 192pxのPNG画像のファイル名です。このファイルは掲示板の<samp>%s</samp>ディレクトリにアップロードする必要があります。',
	'PWA_ICON_LARGE'			=> 'モバイルデバイス用大アイコン',
	'PWA_ICON_LARGE_EXPLAIN'	=> '512px x 512pxのPNG画像のファイル名です。このファイルは掲示板の<samp>%s</samp>ディレクトリにアップロードする必要があります。',
	'PWA_ICON_SIZE_INVALID'		=> '「%s」の画像サイズが正しくありません。',
	'PWA_ICON_MIME_INVALID'		=> '「%s」はPNG画像ファイルである必要があります。',
	'PWA_ICON_INVALID'			=> '「%s」は有効な画像ファイルではないか、想定された場所にありません。ファイル名と場所が正しいことを確認してください。',
	'PWA_ICON_NOT_PROVIDED'		=> '%sフィールドは空欄にできません。すべてのアイコンフィールドに画像を指定する必要があります。',
	'PWA_THEME_COLOURS'			=> 'テーマ &amp; 背景の色（任意）',
	'PWA_THEME_COLOURS_EXPLAIN'	=> 'Webアプリケーションのユーザーインターフェースのデフォルト色を指定するために使用します。テーマ色は、ツールバー、アドレスバー、ステータスバーなど、さまざまなブラウザーUI要素に適用されます。背景色は、アプリケーションのスタイルシートが読み込まれる前にアプリケーションウィンドウに表示されます。',
	'PWA_COLOURS'				=> '色',
	'PWA_THEME_COLOUR'			=> 'テーマ色',
	'PWA_BACKGROUND_COLOUR'		=> '背景色',
	'PWA_INVALID_COLOUR'		=> '色コード「<samp>%s</samp>」は有効な16進数コードではありません。',
	'PWA_SHOW_INSTALL_BANNER'	=> 'アプリのインストールバナーを表示',
	'PWA_SHOW_INSTALL_BANNER_EXPLAIN'	=> '対応しているモバイルデバイスで、掲示板をアプリとしてインストールするよう促すプロンプトを表示します。',
]);
