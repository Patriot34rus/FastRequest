# FastRequest
Модуль для быстрого подключения и получения/передачи данных.
Каждый из видов соединения имеет минимум настроек.
По-умолчания поддерживает такие виды подключения, как:
1) Curl  - Request::TYPE_CURL
2) Socket клиент - Request::TYPE_SOCKET
3) База Данных (PDO) только операции изменения данных. - Request::TYPE_DATA_BASE

По необходимости может быть расширен  работой с файлом, soap и т.д. 

```
$socket = new Request('tcp://localhost:3030', Request::TYPE_SOCKET); 
$socket -> send(['user' => ..., 'password' => ...],'auth');
```
