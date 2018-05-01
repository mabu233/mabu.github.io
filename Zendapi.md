#### `INTERNAL_FUNCTION_PARAMETERS`
zend_execute_data *execute_data, zval *return_value

#### `ZEND_FN(name)`
zif_##name

#### `ZEND_MN(name)`
zim_##name


#### `ZEND_NAMED_FUNCTION(name)`
void name([INTERNAL_FUNCTION_PARAMETERS](#internal_function_parameters))

#### `ZEND_FUNCTION(name)`
**定义一个php函数** &nbsp; [ZEND_NAMED_FUNCTION](#zend_named_functionname) ( [ZEND_FN](#zend_fnname) (name) )  <br/>
**展开** &nbsp; `void zif_name(zend_execute_data *execute_data, zval *return_value)`

#### `ZEND_METHOD(classname,name)`
**定义php类的方法** &nbsp; [ZEND_NAMED_FUNCTION](#zend_named_functionname) ( [ZEND_MN](#zend_mnname) (classname##_##name) ))  <br/>
**展开** &nbsp; `void zim_classname_name(zend_execute_data *execute_data, zval *return_value)`

## 声明类的方法

#### `ZEND_FENTRY(zend_name,name,arg_info,flags)`
{ #zend_name, name, arg_info, (uint32_t) (sizeof(arg_info)/sizeof(struct _zend_internal_arg_info)-1), flags },

#### `ZEND_FE(name, arg_info)`
#### `ZEND_FALIAS(name,alias,arg_info)`
[ZEND_FENTRY](#zend_fentryzend_namenamearg_infoflags)(name, [ZEND_FN](#zend_fnname)(name|alias), arg_info, 0)   <br/>
**展开** &nbsp; `{ "方法名", zif_name, arg_info, (uint32_t) (sizeof(arg_info)/sizeof(struct _zend_internal_arg_info)-1), flags },`

#### `ZEND_ME(classname,name,arg_info,flags)`
#### `ZEND_MALIAS(classname,name,alias,arg_info,flags)`
[ZEND_FENTRY](#zend_fentryzend_namenamearg_infoflags)(name, [ZEND_MN](#zend_mnname)(classname##_##(name|alias)), arg_info, flags)  <br/>
**展开** &nbsp; `{ "方法名", zim_classname_name, arg_info, (uint32_t) (sizeof(arg_info)/sizeof(struct _zend_internal_arg_info)-1), flags },`
<br/><br/>

#### `INIT_CLASS_ENTRY(class_container,class_name,functions)`
**初始化类** &nbsp; INIT_OVERLOADED_CLASS_ENTRY(class_container, class_name, functions, NULL, NULL, NULL)

#### `zend_register_internal_class(zend_class_entry *class_entry)`
**注册类** &nbsp; do_register_internal_class &nbsp; zend_API.c:2704
