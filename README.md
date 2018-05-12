How to install project
======================
1. First clone or download this project to a specific folder within hosting server.
2. Inside the root directory where you can find composer.json file, run this command
    composer install
    Then all of required thinkPHP libraries are available.
3. Now set the working directory(or starting point) to the 'public' directory within the root.
4. Once you are all set, you can run server.

Available Urls implemented right now (for test)
===============================================
+ /index/index/home                         01首页
+ /index/index/register                     02注册
+ /index/index/login                        04登录
+ /index/index/forgotPassword               03忘记密码
+ /index/index/memberCenter                 05会员中心
+ /index/index/projectPublishing            14项目详细页(发布中)
+ /index/index/projectAccepted              15项目详细页(已承接)
+ /index/index/projectComplete              16项目详细页(已完成)
+ /index/index/projectCancel                17项目详细页(已取消)
+ /index/index/complaintSuggestions         29投诉与建议
+ /index/index/newsCenter                   30新闻中心
+ /index/index/loveEnterprise               31爱心企业
+ /index/index/chairtableOrganization       32慈善公益组织
+ /index/index/volunteerGrace               33志愿者
+ /index/index/publicWelfareMall            34公益商城
+ /index/index/addNewCertificate            44新增证书
+ /index/index/message                      40消息
+ /index/index/purchaseInterface            39购买界面
+ /index/index/shoppingCart                 38购物车

+ /index/datamanage/realNameVeri            07实名认证
+ /index/datamanage/qualification           08专业证书
+ /index/datamanage.addressManage           09地址管理
+ /index/datamanage/personalInfo            06个人信息

+ /index/mallmanage/myorders                26订单管理
+ /index/mallmanage/mycollection            27商品收藏
+ /index/mallmanage/productdetail           28商品详情页

+ /index/servicemanage/serviceUndertaken                21承接需求待评价
+ /index/servicemanage/publisherEvaluationOnRequest     19发布者需求待评价
+ /index/servicemanage/undertakerDetails                22承接人详情
+ /index/servicemanage/publish                          10发布需求
+ /index/servicemanage/undertake                        13服务分类
+ /index/servicemanage/showPublished                    18发布的需求
+ /index/servicemanage/showUndertaken                   20承接的服务

+ /index/servicemanage/demandDetailOne      43需求明细1 承接服务
+ /index/servicemanage/releaseRequirement   42需求明细 发布需求
+ /index/servicemanage/messageDetails       41消息详情

+ /index/timemoney/transactionHistory       23时间币明细
+ /index/timemoney/buy                      24时间币充值
+ /index/timemoney/transfer                 25时间币捐赠

+ /index/about_us/index                     35关于我们
+ /index/about_us/local_agencies            36各地机构
+ /index/about_us/recruitment_information   37招聘信息


Available URLs to test(Admin)
==============================
+ /admin/article.types  
+ /admin/article.index
+ /admin/volunteers
+ /admin/love_enterprises
+ /admin/charitable_organizations

## All above are implemented for only view purpose. Functionalities will be deployed further.
