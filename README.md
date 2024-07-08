# Blog Mono

## Công nghệ sử dụng, mục tiêu

```text
Làm 1 trang web có đăng nhập, đăng xuất, phân quyền
có dùng service, repository
gửi email
upload file lên storage, s3
nhập file, xuất file Excel
thực hiện các chức năng theo lịch
đa ngôn ngữ
edit nội dung bài viết: ckeditor
gửi thông báo đến người dùng: notification WebSockets hoặc Server-Sent Events (SSE)
ajax
phân trang
viết file log
viết unittest
request
response
session
validate
redirect
form old message error
model
relationship
try catch exception
migration
seeding
```

## Development Environment
* Debian 11.1  
* apache 2.4.51 (Debian)  
* MySQL 8.0.*  
* PHP 8.0.*  
* Laravel 9.52.*  

## Init project 
- Tạo mấy file docker, cấu hình

- chạy lệnh dưới để tạo dự án laravel, `blog_app` là tên service trong file `docker-compose.yml`

```shell
docker-compose run --rm blog_app composer create-project laravel/laravel
```
- chạy các lệnh như bình thường

- nếu có lỗi ở `storage` thì chạy lệnh:

```shell
chmod -R 777 storage
```
## Settup

1. Create .env for docker-composer
  ```
  $ cp .env.example .env
  ```

1. Build docker 
  ```
  $ docker-compose up -d
  ```
1. Attach app
  ```
  $ docker-compose exec scope_app bash
  ```
1. Settup  
  ```
  $ composer install
  ```
1. Create .env file 
  ```
  $ cp .env.example .env
  ```

1. migrate & seeding  
  ```
  $ php artisan migrate --seed
  ```
1. Public disk setting 
  ```
  $ php artisan storage:link
  ```

|url|
|:-:|-|
|Site:|localhost:8808|

## Docker operations 

* Container start
  `docker-compose up -d`  

* Container stop
  `docker-compose stop`  

* Container attach app
  `docker-compose exec blog_app bash`  

