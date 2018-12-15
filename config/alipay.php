<?php
return [
    'debug' => true,
    'app_id' => "2018011601903092",
    'payment' => [
        'app_id' => "2018011601903092",
        // 商户私钥，您的原始格式RSA私钥
        'merchant_private_key' => "MIIEogIBAAKCAQEAtScbhXonB5JWG8hcz+XTzGeYNZRyYyPvLGuU1ZFEY1j4rJaujKDgJP4pKT0imeblbRTsc+278rTPoH+1wKCupYaw8pnbuy76UPJDETE8csrxVtnOfcsCqUnpVXocTU9a6788FEYBmHtRuaEBCYWwboqZT9+ZBfn70Yiz4V8sYQADstyT6FC+msTvS17R2PJmAoa8OFUAXNX6ZG4VkkGiPuBSWM0zR0MHulsMK4NpFnqQGHWXUzcXHzonAhd5WcocJDXd1GCTyk4Igoz7LQ14lP5U0NhbzdwhfU9T2sZD8Vlclowns0oeNWnc/wVeBkC5cJRjI/QMtlHaHqrHyBU3MQIDAQABAoIBADtMgvDNjKso1vEfRGp1lEBMBQI9Bri3UZqb2MuUUuumezib1qWIH/dcD4NFVOdGMwHjIIfOYYDQeUrTo15b+u+KSLARd2EtvpjyxMuC74OwNRI2GCcLZmKeJDhr0YnpGR0kq3kDdZBBhf4a2ykPzzJQQmFoE0Jmk20Cr8dtjSxu9Hn/bBCalyFruN2HHMqtOPrpf3zc2ia/0R7toAWtKv19/A/hHBvqetbVOAMm+jKZepXbabBzcCpR6nBA++YGIdjOVEFb8FscAeZCXQlWClv5SMAxbRoyiPnCSLuNOb8VNPoqU60fOvLQ0Ze+/fPbSCxeD487s1JJjtBK+QJPJgECgYEA5jEPYOstCyQyfReXgIgICL+S/m9/7jKajfzkXIRBXrzoUuZ03yUZN+TxVvqXSwJp/jjeC7awtOgGxse3c7V6yMtEleMl0VsNU/iuwTMKc+TMvjc3fo1b7LCpBeHtHNLkQLe3lnvYas5sQSIrAGP/fmBCTKOnrBVhDKZfYFqWl9ECgYEAyXaJt4p9nqz294Dm+OUEPUFlJf3b2Ujpk3z/JRG1vuZNX0577+Ir1NqOLVQpKbG9ekonfMSwqsWDcAybU9nddjp/yPFDVLKTI4NKMXvd0I1y6cSSx5wgj4jwNkx/4MUXX7q0kI4OMKzQVHXgKCnfC4zsQ5GfPvc0yLjqJVzn4WECgYBRe+ZkuXstaXfR3isMHZsydZY0KQYRLKqXPoEb2tlFDvvydI/Eed5uccWYs4Yg0E7y5fB8FwSAVW4oqYPgGsrHD2VM5aZ6cI/MPMinrUiy87giUWt8ic+Tfommgm4r+N9BpYcAZwCZ9k5N1CmCruM/OhXeCUSOXtcG4lH6yrZqMQKBgDncB3VlGurldhBjPvKgo3UMQQaGQAvJevvW3FhDG9V1wybqHYIpjLkXA9pU33WQDIEApxKYUrzY+QTHOhz85zO7XVPSpqm+l6+NV1Oa5XuuBCDLSfR2DIvsokCV++wL7siZkJ4rjjvAhhybtgMS83IXyQsm2Xt19/zlzqloY1hBAoGASFLEMwC7hL4SXLub0wS0nRZo4+q+9+qEW1QHeMhN1nIaEuN97qxMYdbmWbA1RlkWvEbiIjiJhPTFtPo3N/6sBW6DFKOShzzUUCLWt5nPeEo+JYpntnms+Bwz3ny8RFmdkWNdSgCz13B5DS073gEGq73qJtvrLqeW94PkVTQE7ec=",
        
        // 异步通知地址
        'notify_url' => config('app.url') ."/charge/notify",
        
        // 同步跳转
        'return_url' => config('app.url') . "/charge/ret",
        
        // 编码格式
        'charset' => "UTF-8",
        
        // 签名方式
        'sign_type' => "RSA2",
        
        // 支付宝网关
        // 沙箱网关
        //'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",
        // 正式网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
        
        // 支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
        'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjLD7Z462HinUVjJ14AbQgqZZP2JHLuUE/p386u52FsnkFbP4cGVLWOsHR37kf9ezHkZljoB7OdAyDWJx0qHZssWQdCyUmj74cimUWhGhfoiEP4TMZ0vYCEEIql28CLgv2/9wrwUqAgMpQjo2cojRI7/jTxdCeyogmzoKGc6UfHay2ApYjWTAeQnONJdiNi2Ej1GD/Vj6P0blUFo1GXMTFX4XKRtQiNaKlNpFguDK/Kf//8L7D4rPX3+UmTBaZ/4Ki3XwhQBBDQjnCyC/E6YWrCzR91t7HMHmckIoF6ePbKBniEqZ4v71QZtA8hl35p/QpA4LmSrkoWz4XOUT/QoABwIDAQAB"
    ]

];
