<?php
class socket
{
    private $task_list = [];
    public $is_run = true;//是否运行
    public $config = array(//各种配置
        'task_num'   => '4',
    );

    public function fork()
    {
        for($i = 0; $i < $this->config['task_num']; $i++){
            $pid = pcntl_fork();
            if($pid === -1){
                exit('fork fail!');
            }else if($pid > 0){
                //主进程创建与子进程通讯socket
                $socket = socket_create(AF_UNIX, SOCK_DGRAM, 0);
                socket_bind($socket, '/tmp/sock_ser_'.$pid.'.sock');
                $this->task_list[$pid] = [
                    'pid' => $pid,
                    'is_run' => false,
                    'sock' => $socket,
                    'sock_path' => '/tmp/sock_' . $pid . '.sock',
                ];
                echo "子进程: " . $pid . "\n";
                continue;
            }else {
                //子进程创建与主进程通讯socket
                $socket = socket_create(AF_UNIX, SOCK_DGRAM, 0);
                $sock_path = '/tmp/sock_' . posix_getpid() . '.sock';
                socket_bind($socket, $sock_path);
                //子进程
                while(true){
                    $buf = '';
                    $form = '';
                    socket_recvfrom($socket, $buf, 1024, 0, $form);
                    echo "子进程(" . posix_getpid() . ")收到:\n" . $buf . "\n\n";
                    //模拟耗时
                    sleep(1);
                    socket_sendto($socket, "ok!\n", 4, 0, $form);
                }
            }
        }
    }

    private function child_ipc()
    {
        $read_list = [];
        foreach ($this->task_list as $taskinfo) {
            array_push($read_list, $taskinfo['sock']);
        }
        $write_list = [];
        $except = [];
        //看看子进程有没有发数据
        socket_select($read_list,$write_list,  $except, 0);
        foreach($read_list as $child_read){
            //找到该子进程对应的客户端sock
            foreach ($this->task_list as &$task) {
                if($child_read === $task['sock']){
                    $buf = '';
                    socket_recvfrom($task['sock'], $buf, 1024, 0, $task['sock_path']);
                    echo "父进程收到:" . $buf . "\n";
                    socket_write($task['cli_sock'], $buf, strlen($buf));
                    //置闲
                    $task['is_run'] = false;
                    break;
                }
            }
        }
    }

    public function close()
    {
        echo "close!\n";
        $this->is_run = false;
    }

    public function start()
    {
        //预先创建子进程
        $this->fork();
        //主进程创建socket
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_bind($socket, '0.0.0.0', 9501);
        socket_listen($socket, 512);
        //无阻塞
        socket_set_nonblock($socket);
        $read_list = [$socket];
        $write_list = [];
        $except = [];
        echo "等待连接: \n";
        while ($this->is_run){
            //读取子进程返回的数据并发送到客户端
            $this->child_ipc();
            $read = $read_list;
            //监听列表
            socket_select($read, $write_list, $except, 0);
            //是否有新连接
            if(in_array($socket, $read)){
                $read_list[] = $cli = socket_accept($socket);
                $key = array_search($socket, $read);
                unset($read[$key]);
            }
            //有可读数据
            while(true && count($read)){
                //读取子进程返回的数据并发送到客户端,这里不读取掉会导致没有空闲task
                $this->child_ipc();
                $task = null;
                //分配一个空闲task
                foreach ($this->task_list as &$taskinfo) {
                    if(!$taskinfo['is_run']){
                        $taskinfo['is_run'] = true;
                        //客户端sock
                        $taskinfo['cli_sock'] = array_shift($read);
                        $task = $taskinfo;
                        break;
                    }
                }
                if($task){
                    //读取客户端数据
                    $data = socket_read($task['cli_sock'], 1024, PHP_BINARY_READ);
                    //发送到子进程
                    socket_sendto($task['sock'], $data, strlen($data), 0, $task['sock_path']);
                }else {
                    usleep(1000);
                }
            }
            usleep(1000);
        }
        socket_close($socket);
        echo "Bye!\n";
    }
}

$socket = new socket();

$socket->start();
