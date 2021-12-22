# APIs 相關資訊
此 APIs 適用於此 [todo-manager](https://github.com/GuanYu914/todo-manager) 專案，下方是每個 API 的格式訊息  
## get_session.php
**呼叫時機** : 拿當前用戶身分  
**要求方法** : GET  
**傳入參數** : 不用  
**回傳格式** : encoded JSON string  
```js
// 回傳資料
{
  isSuccessful : STRING, 'failed' or 'successful'  // request 是否成功
  data         : ARRAY, user data                  // 用戶身分訊息
  msg          : STRING, 'message'                 // 回應訊息
  detail       : STRING, 'detail message for msg'  // 附加回應訊息
}
```

## handle_get_todos.php
**呼叫時機** : 拿用戶的代辦事項  
**要求方法** : GET  
**傳入參數** : SESSION COOKIE  
**回傳格式** : encoded JSON string  
```js
// 回傳資料
{
  isSuccessful : STRING, 'failed' or 'successful'  // request 是否成功
  data         : 2d ARRAY, current user todos      // 當前用戶所有代辦事項 
  msg          : STRING, 'message'                 // 回應訊息
  detail       : STRING, 'detail message for msg'  // 附加回應訊息
}
```

## handle_login.php
**呼叫時機** : 用戶登入，建立 SESSION 連線  
**要求方法** : POST  
**傳入欄位** : account, password  
**回傳格式** : encoded JSON string    
```js
// 回傳資料
{
  isSuccessful : STRING, 'failed' or 'successful'  // request 是否成功
  displayError : BOOLEAN, 'true' or 'false'        // 告訴前端是否要顯示此錯誤
  msg          : STRING, 'message'                 // 回應訊息
  detail       : STRING, 'detail message for msg'  // 附加回應訊息
}
```

## handle_logout.php
**呼叫時機** : 用戶登出，摧毀 SESSION 連線  
**要求方法** : GET  
**傳入參數** : 不用   
**回傳格式** : encoded JSON string  

## handle_register_login.php
**呼叫時機** : 處理用戶註冊完後登入，並建立 SESSION 連線   
**要求方法** : POST  
**傳入欄位** : nickname, account, password  
**回傳格式** : encoded JSON string    
```js
// 回傳資料
{
  isSuccessful : STRING, 'failed' or 'successful'  // request 是否成功
  msg          : STRING, 'message'                 // 回應訊息
  detail       : STRING, 'detail message for msg'  // 附加回應訊息
}
```

## handle_register.php
**呼叫時機** : 處理用戶註冊  
**要求方法** : POST  
**傳入欄位** : nickname, account, password  
**回傳格式** : encoded JSON string  
```js
// 回傳資料
{
  isSuccessful : STRING, 'failed' or 'successful'  // request 是否成功
  displayError : BOOLEAN, 'true' or 'false'        // 告訴前端是否要顯示此錯誤
  msg          : STRING, 'message'                 // 回應訊息
  detail       : STRING, 'detail message for msg'  // 附加回應訊息
}
```

## handle_store_todos.php
**呼叫時機** : 儲存當前用戶所有代辦事項  
**要求方法** : POST  
**傳入欄位** : content，格式為 encoded JSON string  
**回傳格式** : encoded JSON string  
```js
// 回傳資料
{
  isSuccessful : STRING,  'failed' or 'successful' // request 是否成功
  msg          : STRING, 'message'                 // 回應訊息
  detail       : STRING, 'detail message for msg'  // 附加回應訊息
} 
```

## handle_update_user.php
**呼叫時機** : 處理用戶更新個人資料   
**要求方法** : POST  
**傳入參數** : nickname, password  
**回傳格式** : encoded JSON string  
```js
// 回傳資料
{
  isSuccessful : STRING, 'failed' or 'successful'  // request 是否成功
  msg          : STRING, 'message'                 // 回應訊息
  detail       : STRING, 'detail message for msg'  // 附加回應訊息
}
```