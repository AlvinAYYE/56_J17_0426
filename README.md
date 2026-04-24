# J17 第二堂：路網圖與 CRUD

這個資料夾是第 55 屆 J17「Public Transit Query System」練習教材。

主題分成兩段：

- 上午：路網圖切版、Vue 資料渲染、PHP JSON API。
- 下午：MySQL/MariaDB、PDO、站點/車輛/路線 CRUD。

## 建議上課順序

1. `課程.md`
2. `01-network-static.html`
3. `02-network-vue.html`
4. `api/network.php`
5. `03-network-fetch.html`
6. `sql/init.sql`
7. `04-stations.html`
8. `05-buses.html`
9. `06-routes.html`

## 開啟方式

請透過 XAMPP 的 Apache 用 `localhost` 開啟 HTML，不建議直接雙擊檔案。

範例：

```text
http://localhost/J17_第二次上課/.claude/worktrees/adoring-goldwasser-8eae57/03-network-fetch.html
```

如果你把教材移到別的資料夾，請依照實際路徑調整網址。

## 資料庫準備

1. 開啟 XAMPP 的 Apache 與 MySQL。
2. 進入 phpMyAdmin。
3. 匯入 `sql/init.sql`。
4. 再開啟 `04-stations.html`、`05-buses.html`、`06-routes.html` 操作 CRUD。

PDO 連線設定在 `api/db.php`：

```php
$host = 'localhost';
$dbname = 'ptqs';
$user = 'root';
$pass = '';
```

這是 XAMPP 常見預設值。

## 主要檔案

- `01-network-static.html`：純 HTML/CSS 版路網圖。
- `02-network-vue.html`：Vue 由資料產生路網圖。
- `03-network-fetch.html`：Vue fetch PHP 路網圖 API。
- `api/network.php`：板南線亂數路網資料 API。
- `sql/init.sql`：資料庫與初始資料。
- `api/stations.php`：站點 CRUD API。
- `api/buses.php`：車輛 CRUD API。
- `api/routes.php`：路線 CRUD API。
- `04-stations.html`：站點管理。
- `05-buses.html`：車輛管理。
- `06-routes.html`：路線管理。

## 技術規則

- 使用本地 `vue.3.5.13.js`。
- 使用 Vue Composition API。
- fetch 寫法使用 `.then().then().catch()`。
- PHP API 回傳 JSON。
- CRUD 使用 MySQL/MariaDB 與 PDO。
