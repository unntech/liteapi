
LiteApi 1.0
===============

[![Total Downloads](https://poser.pugx.org/lzs9605/liteapi/downloads)](https://packagist.org/packages/lzs9605/liteapi)
[![Latest Stable Version](https://poser.pugx.org/lzs9605/liteapi/v/stable)](https://packagist.org/packages/lzs9605/liteapi)
[![PHP Version](https://img.shields.io/badge/php-%3E%3D7.0-8892BF.svg)](http://www.php.net/)
[![License](https://poser.pugx.org/lzs9605/liteapi/license)](https://packagist.org/packages/lzs9605/liteapi)

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
composer create-project lzs9605/liteapi yourApp
~~~

~~~
将目录config.sample 改名为 config，可以更据需求增加配置文件
读取例子见：tests/getconfig.sample.php
~~~


启动服务

~~~
cd yourApp
./app.sh start
~~~

然后就可以在浏览器中访问

~~~
http://localhost:9898
~~~

如果需要更新框架使用
~~~
composer update lzs9605/litephp
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

