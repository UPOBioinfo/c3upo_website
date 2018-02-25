<?php if (!class_exists('CaptchaConfiguration')) { return; }

// BotDetect PHP Captcha configuration options
        
return [
  // Captcha configuration for example page
  'RegisterCaptcha' => [
    'UserInputID' => 'captchaCode',
    'ImageWidth' => 250,
    'ImageHeight' => 50,
    'HelpLinkMode' => HelpLinkMode::Image,
    'HelpLinkEnabled' => false
  ],

];