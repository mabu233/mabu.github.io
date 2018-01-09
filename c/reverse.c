//
// Created by xiao on 18-1-8.
//
#include <stdio.h>
#include <string.h>

char* reverse(char* str);

int main(){
    char str[12] = "hello,world";
    printf("%s\n", reverse(str));
    return 0;
}

char* reverse(char* str){
    int len = 0;
    len = strlen(str);
    for(int i = 0; i <= len/2; i++){
        char ch = str[i];
        str[i] = str[len - i - 1];
        str[len - i - 1] = ch;
    }
    return str;
}
