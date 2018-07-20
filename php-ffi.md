**php-ffi 安装笔记**
- 需要 php7.3
- libffi-3.*

**先编译安装 libffi**
1. 下载 libffi,直接从git上拉开发版本
2. run ./autogen.sh, 需要 install autoconf, automake and libtool
3. ./configure --prefix=/usr/local/ffi
4. make && make install
- make 时出错，不知道为啥，通过 [libffi issues](https://github.com/libffi/libffi/issues/399) 解决

**编译php-ffi**
1. phpize
2. ./configure --with-ffi=/usr/local/ffi/lib/libffi-3.2.1
3. make
4. make test
5. make install
- 多版本php需注意指定php版本, --with-ffi 指向的目录下 有 include 文件夹，里面有 ffi.h 和 ffitarget.h

**ok**
