<?php

declare(strict_types=1);

namespace App\Helpers\Enums;
//网络状态码错误
enum TrueCode: int
{
        //1xx消息
    case  CONTINUE = 100; //：继续（Continue）
        //客户端应当继续发送请求。客户端应当继续发送请求的剩余部分，或者如果请求已经完成，忽略这个响应。
    case SWIT_PRO =  101; //转换协议（Switching Protocols）
        //在发送完这个响应最后的空行后，服务器将会切换到在Upgrade 消息头中定义的那些协议。只有在切换新的协议更有好处的时候才应该采取类似措施。
    case PROCESSING =  102; //继续处理（Processing）
        //由WebDAV（RFC 2518）扩展的状态码，代表处理将被继续执行。
        // 2xx成功
    case SUCCESS = 200; //：请求成功（OK）

        // 获得响应的内容，进行处理（重要）
    case CREATE =  201; //：创建（Created）

        // 请求已经被实现，而且有一个新的资源已经依据请求的需要而建立，且其URI已经随Location头信息返回。
    case ACCEPTED =  202; //：请求被接受（Accepted）

        //服务器已接受请求，但尚未处理。最终该请求可能会也可能不会被执行，并且可能在处理发生时被禁止。处理方式：阻塞等待
    case NO_CONTENT =  204; //：没有内容（No Content）

        //服务器成功处理了请求，没有返回任何内容。如果客户是用户代理，则无须为此更新自身的文档视图。 处理方式：丢弃
        //3xx重定向
    case MULTIPLE_CHOICES =  300; //：多重选择（Multiple Choices）
        // 该状态码不被HTTP/1.0的应用程序直接使用， 只是作为3XX类型回应的默认解释。存在多个可用的被请求资源。 处理方式：若程序中能够处理，则进行进一步处理，如果程序中不能处理，则丢弃
    case MOVED_PERMANENTLY =  301;   //：永久重定向（Moved Permanently）
        // 请求到的资源都会分配一个永久的URL，这样就可以在将来通过该URL来访问此资源 处理方式：重定向到分配的URL（永久重定向，重要）
    case FOUND =  302; //：建立（Found）
        // 请求到的资源在一个不同的URL处临时保存 处理方式：重定向到临时的URL（临时重定向，重要）
    case NOT_NOTMODIFIED =  304;  //：未修改（Not Modified）
        //请求的资源未更新 处理方式：丢弃，使用本地缓存文件（没有发送请求，用的是本地缓存文件，重要）
        //4xx客户端错误
    case BAD_REQUEST = 400; //：非法请求（Bad Request）
        //处理方式：丢弃
    case UNAUTHORIZED = 401; //：未授权（Unauthorized）
        //处理方式：丢弃
    case FORBIDDEN = 403; // ：禁止（Forbidden）
        // 服务器已经理解请求，但是拒绝执行它。处理方式：丢弃（重要）
    case NOT_FOUND =  404; //：没有找到（Not Found）
        // 请求失败，请求所希望得到的资源未被在服务器上发现，但允许用户的后续请求。处理方式：丢弃（重要）
    case NOT_ALLOWED=  405; //：方法未允许（Method Not Allowed）
        //请求行中指定的请求方法不能被用于请求相应的资源。
        //5xx服务器错误
    case SERVER_ERROR = 500; //：服务器内部错误（Internal Server Error）
        //服务器内部错误 服务器遇到了一个未曾预料的状况，导致了它无法完成对请求的处理。一般来说，这个问题都会在服务器端的源代码出现错误时出现。（服务器问题，代码有问题，重要）
    case NOT_IMPLEMENTED = 501; //：服务器无法识别（Not Implemented）
        //服务器无法识别 服务器不支持当前请求所需要的某个功能。当服务器无法识别请求的方法，并且无法支持其对任何资源的请求。
    case BAD_GATEWAY = 502; //：错误网关（Bad Gateway）
        //作为网关或者代理工作的服务器尝试执行请求时，从上游服务器接收到无效的响应。
    case GATEWAY_TIMEOUT = 503;  // ：网关超时（Gateway Timeout）

    case DATA_ERROR = 422;  // ：表单验证失败
    //服务出错 由于临时的服务器维护或者过载，服务器当前无法处理请求。这个状况是临时的，并且将在一段时间以后恢复。
}
