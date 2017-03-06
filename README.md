# PHPSphinx的安装使用

------
## 在windows操作系统下的安装使用
* 1.下载sphinx [下载地址](http://sphinxsearch.com/downloads/release/) 下载适合你的Windows版本的sphinx（32位还是64位）
* 2.将其解压到D:\sphinx(你自己选择路径，这里是我的路径)

> * 目录如下
> * bin（配置文件夹）
> * data（数据文件夹）
> * log（日志文件夹）
> * share（资源文件夹）

* 3.将D:\sphinx\sphinx.cong.in复制到D:\sphinx\bin\sphinx.conf.in，并重命名为sphinx.conf
* 4.修改 D:\sphinx\bin\sphinx.conf 如下：

```sphinx.conf
## 数据源src1(用户数据源)
source src
{
	type			= mysql

	sql_host		= localhost
	sql_user		= root
	sql_pass		= root
	sql_db			= test
	sql_port		= 3306

	sql_query_pre		= SET NAMES utf8
}

source user : src
{
	sql_query		= \
		SELECT id, oname, post, role, uname, name, phone, content, card, address, qq, wechat, email, school, major \
		FROM user

	sql_attr_uint		= id

	# sql_attr_string	= name
	sql_attr_string 	= card
	sql_attr_string		= address
}

source workflow : src
{
	sql_query		= \
		SELECT id, work_flow, model_flow, tags, creator \
		FROM work_flow

	sql_attr_uint		= id

	sql_attr_string		= creator
}

source flow : src
{
	sql_query		= \
		SELECT id, flow_num, flow_name, flow_desc, creator \
		FROM flow

	sql_attr_uint		= id

	sql_attr_string		= creator
}

index user
{
	source			= user

	path			= D:/sphinx/data/user

	docinfo			= extern

	dict			= keywords

	mlock			= 0

	morphology		= none

	min_word_len		= 1

	charset_type		= utf-8

	ngram_len		= 1

	ngram_chars		= U+3000..U+2FA1F
}

index flow : user
{
	source			= flow

	path			= D:/sphinx/data/flow
}

index workflow : user
{
	source			= workflow

	path			= D:/sphinx/data/workflow
}

index rt
{
	type			= rt

	path			= D:/sphinx/data/rt

	rt_field		= name
	rt_field		= tags
	rt_field		= flow_name
	rt_field		= flow_desc
}

indexer
{
	mem_limit		= 128M
}

searchd
{
	listen			= 9312
	listen			= 9306:mysql41

	log			= D:/sphinx/log/searchd.log

	query_log		= D:/sphinx/log/query.log

	read_timeout		= 5

	client_timeout		= 300

	max_children		= 30

	persistent_connections_limit	= 30

	pid_file		= D:/sphinx/log/searchd.pid

	seamless_rotate		= 1

	preopen_indexes		= 1

	unlink_old		= 1

	mva_updates_pool	= 1M

	max_packet_size		= 8M

	max_filters		= 256

	max_filter_values	= 4096

	max_batch_queries	= 32

	workers			= threads
}


# --eof--

```

* 5.导入测试数据将D:\sphinx\example.sql中语句执行到test数据库中，注意：test数据库创建时需要指定为utf-8格式；
* 6.打开cmd窗口，进入目录D:\sphinx\bin；
* 7.建立索引（在/bin/文件目录下）

> * indexer sphinx.conf test1 （创建指定索引）
> * indexer sphinx.conf --all （创建配置文件中设置的所有索引）

* 8.开启搜索服务，执行 searchd sphinx.config

> * searchd sphinx.conf （开启搜索服务）

------
## 在linux操作系统下的安装使用（ubuntu操作系统下安装）
* 1.安装全文搜索引擎 Sphinx 前，必须先安装 MySQL server 并设置数据库 root 用户(此处不讲 MySQL server 的具体安装过程，请关注相关主题)
* 2.使用 apt-get 方法直接安装 Sphinx:（安装后sphinxsearch在/etc/下）

> * apt-get install sphinxsearch

* 3.将D:\sphinx\sphinx.cong.in复制到D:\sphinx\bin\sphinx.conf.in，并重命名为sphinx.conf
* 4.修改 D:\sphinx\bin\sphinx.conf 如下：

```sphinx.conf
## 数据源src1(用户数据源)
source src
{
	type			= mysql

	sql_host		= localhost
	sql_user		= root
	sql_pass		= root
	sql_db			= test
	sql_port		= 3306

	sql_query_pre		= SET NAMES utf8
}

source user : src
{
	sql_query		= \
		SELECT id, oname, post, role, uname, name, phone, content, card, address, qq, wechat, email, school, major \
		FROM user

	sql_attr_uint		= id

	# sql_attr_string	= name
	sql_attr_string 	= card
	sql_attr_string		= address
}

source workflow : src
{
	sql_query		= \
		SELECT id, work_flow, model_flow, tags, creator \
		FROM work_flow

	sql_attr_uint		= id

	sql_attr_string		= creator
}

source flow : src
{
	sql_query		= \
		SELECT id, flow_num, flow_name, flow_desc, creator \
		FROM flow

	sql_attr_uint		= id

	sql_attr_string		= creator
}

index user
{
	source			= user

	path			= D:/sphinx/data/user

	docinfo			= extern

	dict			= keywords

	mlock			= 0

	morphology		= none

	min_word_len		= 1

	charset_type		= utf-8

	ngram_len		= 1

	ngram_chars		= U+3000..U+2FA1F
}

index flow : user
{
	source			= flow

	path			= D:/sphinx/data/flow
}

index workflow : user
{
	source			= workflow

	path			= D:/sphinx/data/workflow
}

index rt
{
	type			= rt

	path			= D:/sphinx/data/rt

	rt_field		= name
	rt_field		= tags
	rt_field		= flow_name
	rt_field		= flow_desc
}

indexer
{
	mem_limit		= 128M
}

searchd
{
	listen			= 9312
	listen			= 9306:mysql41

	log			= D:/sphinx/log/searchd.log

	query_log		= D:/sphinx/log/query.log

	read_timeout		= 5

	client_timeout		= 300

	max_children		= 30

	persistent_connections_limit	= 30

	pid_file		= D:/sphinx/log/searchd.pid

	seamless_rotate		= 1

	preopen_indexes		= 1

	unlink_old		= 1

	mva_updates_pool	= 1M

	max_packet_size		= 8M

	max_filters		= 256

	max_filter_values	= 4096

	max_batch_queries	= 32

	workers			= threads
}


# --eof--

```

* 5.到sphinx.conf 文件所在目录，进行下列操作
* 6.建立索引（在/bin/文件目录下）

> * indexer test1 （创建指定索引）
> * indexer --all （创建配置文件中设置的所有索引）

* 7.开启 sphinxsearch 功能：

> * vim /etc/default/sphinxsearch
> * 将其中的 START=no
> * 改为 START=yes

* 8.开启搜索服务，执行 searchd sphinx.config

> * service sphinxsearch start （开启搜索服务）

* 9.如果服务启动出错（已经启动或正在运行）

> * ps aux|grep searchd 查看进程
> * kill -9 PID号 强制关闭进程
> * killall searchd 关闭所有事searchd的进程
> * 再去执行上面的第8步

## 安装sphinxsearch的api

* 1.composer安装

> * composer require neutron/sphinxsearch-api