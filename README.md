# Sphinx安装配置

## Windows操作系统下的安装

* 1、到这个网址 http://sphinxsearch.com/downloads/release/ 下载适合你的Windows版本的sphinx（32位还是64位）
* 2、将其解压到D:\sphinx (你自己选择路径，这里是我的路径)
 >目录下有4个文件夹（data、bin、log、）
* 3、将D:\sphinx\sphinx.cong.in复制到D:\sphinx\bin\sphinx.conf.in，并重命名为sphinx.conf
* 4、修改 D:\sphinx\bin\sphinx.conf 如下：
* 5、导入测试数据将D:\sphinx\example.sql中语句执行到test数据库中，注意：test数据库创建时需要指定为utf-8格式；
* 6、打开cmd窗口，进入目录D:\sphinx\bin；
* 7、建立索引，执行indexer test1，test1即为sphinx.conf中index test1（indexer sphinx.conf --all 创建所有表的索引）
* 7、开启搜索服务，执行 searchd sphinx.config
## Linux操作系统下的安装

* 1、
* 2、
* 3、
