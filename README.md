# ZDPHP

## PHP轻量级开发框架
### 开发前设置：   
 将Zdlib目录放到任意目录，在php.ini 中 “include_path=”将Zdlib和Zdweb目录添加进去。

   Zdlib为类库文件<br>
   Zdweb为开发中使用的model和Controller文件<br>
   将web目录拷贝到webroot下面，此目录为view层文件，用户访问的是此目录，不会访问到Zdlib与Zdweb目录。<br>
#### 1. PHP MVC LIB  
PHP MVC类库目录结构如下
``` 
――Zdlib
  |--Exception
  |--PHPExcel
  |--PHPRPC
  |--Rest
  |--Selftemplate
  |--Smarty
  |--Utils
  |--Interface
```
说明：
* Exception：异常类目录
* PHPExcel：PHPExcel目录
* PHPRPC：RPC实现
* Rest：Rest简单实现
* Smarty:Smarty
* Utils:相关工具类(可扩展)
* Utils/Interface：接口文件（抽象类）<br>
PHP MVC LIB目录为框架核心目录，一般不添加文件，除非自己维护框架

#### 2. PHP显示层(View)<br>
PHP显示层开发目录结构如下
```
――web
  |--include
  |--images
  |--css
  |--javascript
  |--modulea
  |--moduleb
```
说明：
* web：位于webroot根目录下
* include：存放资源文件
* image：存放前台页面图片
* css：存放页面CSS文件
* javascript：存放javascript文件
* moduleXXX：存放具体频道<br>
各个目录下可以自行建立目录，目录名称要求必须是小写字母形式

#### 3. PHP开发模型层(Model)与控制层(Controller)
PHP开发模型层与控制层如下
```
――Zdweb
  |――Action
  |――Dao
  |――Common
  |――Lang
```
说明：
* Action：控制层目录，其下文件夹按照模块名称进行创建，其中建立的每个action首字母小写，后缀以Action结束，例如indexAction.php。
* Dao：模型层目录，其下文件夹按照模块名称创建，其中建立的每个dao首字以Dao结束，例如：UserInfoDao.php
* Common：Action和Dao之外自定义的实用工具类或函数文件<br>
模型层与控制层目录名称要求全部是英文字母且首字母必须为大写，如后面跟有其余单词，要求其余单词首字母大写，其余小写(驼峰)
