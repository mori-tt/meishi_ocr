# laravel-line-handson-2024

本リポジトリは下記ハンズオンイベントで利用するソースコードです

https://linedevelopercommunity.connpass.com/event/313579/

## 推奨エディター

- VSCode

> 上記エディターのみ確認しています

## 実行

### インストール & コンテナ起動

```bash
make
```

> make は下記のエイリアスです
>
> ```
> make install
> make down
> make copy-env-if-not-exist
> make up
> ```

<details>

<summary>各コマンドについて</summary>

### インストールのみ

```bash
make install
```

### .env 生成

```bash
make copy-env-if-not-exist
```

### コンテナ起動

```bash
make up
```

### コンテナ終了

```bash
make down
```

</details>

## ポートフォワード

ローカルで開発する際に、特定のポートをグローバルに公開して動作確認することができます

> 事前に [devtunnel](https://learn.microsoft.com/ja-jp/azure/developer/dev-tunnels/get-started?tabs=macos) をインストールしてください

```bash
make tunnel
```

## 環境変数

|項目名|値|備考|
|--|--|--|
|APP_URL|`https://*****-20080.asse.devtunnels.ms`|devtunnelの`Connect via browser`に表示されるURLを設定する|
|LINE_CHANNEL_SECRET|`*****`|[LINE Developers](https://developers.line.biz/ja/) > チャネル基本設定 > チャネルシークレット|
|LINE_CHANNEL_ACCESS_TOKEN|`*****`|[LINE Developers](https://developers.line.biz/ja/) > Messaging API 設定 > チャネルアクセストークン|
|CLAUDE_API_KEY|`sk-*****`|[Anthropic Console > API Keys](https://console.anthropic.com/settings/keys) から発行する|

## Dev Container

コンテナ起動後に `laravel.test-1` にアタッチする

### 操作イメージ

<details>

<summary>クリックして表示</summary>

![](./docs/img/image001.png)
![](./docs/img/image002.png)
![](./docs/img/image003.png)
![](./docs/img/image004.png)

> `Container ~~~` と表示されていれば OK

</details>

## システム概要

### 全体像

```mermaid
graph RL
  subgraph ユーザー
    LINEアプリ
  end
  subgraph LINE社
    サーバー
  end
  subgraph 開発
    Laravel
  end
  subgraph 外部
    Claude
  end

  サーバー -->|Webhook| Laravel
  Laravel -->|MessagingAPI| サーバー --> LINEアプリ
  Laravel -->|API| Claude
  Claude --> Laravel
  LINEアプリ ---> サーバー
```

### クラス依存関係

```mermaid
graph LR
  Controller
  UseCase
  Repository
  Model

  LINEサーバー -->|Webhook Request| Controller
  Controller -->|Event| EventHandler
  EventHandler -->|Message| UseCase
  UseCase -->|ApiRequest| Infrastructure/Api
  Infrastructure/Api --- ClaudeAPI
  UseCase --> Infrastructure/Databases
  Infrastructure/Databases --> Repository
  Repository --> Model
  Model --- DB

  Model -->|Collection| Repository
  Repository -->|Entity| Infrastructure/Databases
  Infrastructure/Databases -->|Domain| UseCase
  Infrastructure/Api -->|ApiResponse| UseCase
  UseCase -->|Message| EventHandler
  EventHandler -->|Message| Controller
  Controller -->|MessagingAPI| LINEサーバー
```
