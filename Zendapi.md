#### `INTERNAL_FUNCTION_PARAMETERS`
zend_execute_data *execute_data, zval *return_value

#### `ZEND_FN(name)`
zif_##name

#### `ZEND_MN(name)`
zim_##name

#### `ZEND_NAMED_FUNCTION(name)`
void name([INTERNAL_FUNCTION_PARAMETERS](#internal_function_parameters))

#### `ZEND_FUNCTION(name)`
**定义一个php函数** &nbsp; [ZEND_NAMED_FUNCTION](#zend_named_functionname) ( [ZEND_FN](#zend_fnname) (name) )<br/>
**展开** &nbsp; void zif_name(zend_execute_data *execute_data, zval *return_value)

#### `ZEND_METHOD(classname,name)`
**定义php类的方法** &nbsp; [ZEND_NAMED_FUNCTION](#zend_named_functionname) ( [ZEND_MN](#zend_mnname) (classname##_##name) ))<br/>
**展开** &nbsp; void zim_classname_name(zend_execute_data *execute_data, zval *return_value)<br/>
