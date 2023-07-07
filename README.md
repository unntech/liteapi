
LiteApi 1.0
===============

[![Total Downloads](https://poser.pugx.org/unntech/liteapi/downloads)](https://packagist.org/packages/unntech/liteapi)
[![Latest Stable Version](https://poser.pugx.org/unntech/liteapi/v/stable)](https://packagist.org/packages/unntech/liteapi)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/unntech/liteapi/license)](https://packagist.org/packages/unntech/liteapi)

基于PHP Swoole 创建的协程框架，可用于生产环境的高性能API接口。



## 主要新特性

* 采用`PHP7`强类型（严格模式）
* 支持更多的`PSR`规范
* 原生多应用支持
* 对Swoole以及协程支持
* 对IDE更加友好
* 统一和精简大量用法


> LiteApi 1.0的运行环境要求PHP7.0+，兼容PHP8.1
> 需要安装 ext-swoole 扩展

## 安装

~~~
composer create-project unntech/liteapi yourApp
~~~

~~~
将目录config.sample 改名为 config，可以更据需求增加配置文件
读取例子见：tests/sample.config.php
~~~


启动HttpApi服务

~~~
cd yourApp
./http.sh start    (#chmod +x http.sh 先赋予可执行权限)
~~~

然后就可以在浏览器中访问

~~~
http://localhost:9898/authorize  #获取TOKEN

http://localhost:9898/sampleApi/test
~~~

~~~
访问的路径对应/api/controller/文件名/函数名
~~~

启动WebSocket服务

~~~
cd yourApp
./websocket.sh start    (#chmod +x websocket.sh 先赋予可执行权限)
~~~

然后就可以使用进行websocket连接
~~~
ws://localhost:9899
~~~

~~~
/api/WebSocket.php 对应方法处理相应的事件过程
~~~
如果需要更新框架使用
~~~
composer update unntech/litephp
~~~

目录结构
~~~
yourApp/
├── api                                     #LiteApi命名空间
│   ├── controller                          #Api接口控制器方法目录，支持分项多级子目录
│   ├── ...                                 #其它子模块
│   ├── HttpApi.php                         #接口调用类
│   ├── HttpRequest.php                     #控制器调用基础类
│   ├── LiApiCommVar.php                    #共享公用变量类
│   ├── LiteApi.php                         #LiteApi通用类，自动载入，默认全局变量$Lite
│   ├── WebSketRepo.php                     #LiWebSocket response 基础类
│   ├── WebSocket.php                       #LiWebSocket 基础类
├── config                                  #配置文件
│   ├── app.php                             #项目基础配置
│   ├── db.php                              #数据库配置文件
│   ├── redis.php                           #redis配置文件
│   ├── websocket.php                       #WebSocket配置文件
│   ├── httpapi.php                         #HttpApi配置文件
├── docs                                    #文档
│   ├── liteapi.sql                         #基础数据库表
├── include                                 #通用函数库
│   ├── common.php                          #全局通用函数
├── log                                     #日志目录
├── tests                                   #测试样例，可删除
├── vendor                                  #composer目录
├── autoload.php                            #autoload载入主程序
├── composer.json                           #
├── http.sh                                 #HttpApi启动命令
├── httpapi.php                             #HttpApi主程序
├── websocket.php                           #websocket主程序
├── websocket.sh                            #WebSocket启动命令
└── README.md
~~~

## 文档

[完全开发手册](#)

## 命名规范

`LiteApi`遵循PSR-2命名规范和PSR-4自动加载规范。

## 参与开发

直接提交PR或者Issue即可

## 版权信息

LiteApi遵循Apache2开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

版权所有Copyright © 2022 by Jason Lin All rights reserved。

