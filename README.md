# 画像解析API返り値のDB保存

このリポジトリは、画像解析APIの返り値をDBに保存する処理をもつLaravelベースのアプリケーションです。プロジェクトには、API通信、エラーハンドリング、ログ記録、解析結果のデータベース保存といった機能が含まれています。また、成功、失敗、バリデーションエラーといった条件下でのAPI動作を確認するためのテストが含まれています。

## 目次

- [前提条件](#前提条件)
- [APIエンドポイント](#apiエンドポイント)
- [ログ記録とエラーハンドリング](#ログ記録とエラーハンドリング)
- [テストの実行](#テストの実行)
- [プロジェクト構成](#プロジェクト構成)

## 前提条件

- PHP 8.0以上
- Laravel 11
- Composer
- PostgresやMySQL・SQLiteなどサポートされているデータベース

## APIエンドポイント

アプリケーションは画像解析のために次のエンドポイントを提供します:

- **POST** `/analyze`

    **ルート定義:**

    ```php
    Route::post('/analyze', [ImageAnalysisController::class, 'analyze']);
    ```

    **リクエストパラメータ:**

    - `image_path` (string, 必須): 解析する画像のパス。

    **リクエスト例:**

    ```json
    {
        "image_path": "path/to/your/image.jpg"
    }
    ```

## ログ記録とエラーハンドリング

- **成功時:** 画像パス、成功ステータス、メッセージ、解析結果などの情報がデータベースに保存されます。
- **失敗時:** API通信中やデータベース保存中に発生したエラーはエラーログに記録され、JSON形式のエラーレスポンスが返されます。

## テストの実行

1. PHPUnitを使用してテストを実行します:

    ```bash
    php artisan test
    ```

2. テストには以下が含まれます:
   - モック画像解析apiから返り値のDBへの保存成功
   - モック画像解析apiから特定のエラーを伴う画像解析の失敗
   - 画像パスが空の場合のバリデーションエラー

## プロジェクト構成

- `app/Http/Controllers/ImageAnalysisController`: 画像解析リクエストを処理するコントローラーが含まれています。
- `app/Services/ImageAnalysisService`: 外部APIの呼び出しと結果のDB保存・ログ記録を担当するサービスが含まれています。
- `app/Models/AIAnalysisLog`: DB操作を行う為のモデルが含まれています。
- `tests/Feature/ImageAnalysisTest`: 画像解析エンドポイントの機能テストが含まれています。
- `config/services`: 使用しているapiのURLベースは定数としてenvから取得してbase_urlとして設定しています。



