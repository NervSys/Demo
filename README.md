# Demo

A simple demo project for [Nervsys](https://github.com/NervSys/NervSys) 7.4.4+

demo URL: http://your_domain/api.php/user/login/normal?account=demo&passwd=demo

## installation

1. clone [Nervsys](https://github.com/NervSys/NervSys)  
2. in the same directory, clone [Demo](https://github.com/NervSys/Demo)  
3. check and correct the required path to "sys.php" in "Demo/home/api.php"  
4. start up any server program and set the root to "Demo/home"  
5. visit URL: http://your_domain/api.php/user/login/normal?account=demo&passwd=demo, or, call api.php via CLI: php api.php -r -c"user/login/normal" -d"account=demo&passwd=demo"