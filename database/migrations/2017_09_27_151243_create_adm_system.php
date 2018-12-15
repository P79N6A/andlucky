<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmSystem extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 广告分类表
        Schema::create('adm_category', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('title_en', 255)->nullable();
            $table->smallInteger('sort', false, true)->default(0);
            $table->tinyInteger('display', false, true)->default(1);
            $table->timestamps();
        });
        
        // 广告内容表
        Schema::create('adm_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true); // 用户ID
            $table->string('title', 255); // 标题
            $table->smallInteger('category_id', false, true);
            $table->decimal('total_price');
            $table->decimal('click_price');
            $table->integer('click_times', false, true)->default(1); // 总点击次数
            $table->integer('has_click_times', false, true)->default(0); // 已点击次数
            $table->smallInteger('last_days', false, true); // 持续天数
            $table->timestamp('start_date')->nullable(); // 开始日期
            $table->timestamp('end_date')->nullable(); // 结束日期
            $table->integer('start_hour'); // 开始日间
            $table->integer('end_hour'); // 结束时间
            $table->string('cover', 255); // 广告封面图
            $table->text('posts_content'); // 广告介绍活动介绍
            $table->string('url', 255)->nullable(); // 活动地址
            $table->tinyInteger('pay_status', false, true)->default(0); // 支付状态
            $table->tinyInteger('display', false, true)->default(1); // 是否显示
            $table->smallInteger('sort', false, true)->default(0); // 排序
            $table->smallInteger('up_times', false, true)->default(0); // 赞的次数
            $table->smallInteger('down_times', false, true)->default(0); // 踩的次数
            $table->timestamps();
        });
        
        // 用户点击记录
        Schema::create('adm_post_click_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_id', false, true);
            $table->integer('user_id', false, true);
            $table->decimal('click_reward'); // 点击报酬
            $table->timestamps();
        });
        
        // 用户收入表
        Schema::create('adm_user_currency', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->string('event', 50); // 事务类型 充值/发布/转换铜板
            $table->integer('target_id', false, true); // 目标id
            $table->string('target_desc', 255); // 目标说明 或者标题
            $table->string('act', 20); // 收入类型 in / out
            $table->decimal('cash'); // 收支记录信息
            $table->timestamps();
        });
        
        /**
         * 用户充值记录表
         */
        Schema::create('adm_user_cash', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->string('event', 50); // 事务类型
            $table->integer('target_id', false, true); // 目标id
            $table->string('target_desc', 255); // 充值单号 或者 兑换说明
            $table->string('act', 20); // 收入类型 in / out
            $table->decimal('cash'); // 收支记录信息
            $table->decimal('extra_cash'); // 收支赠送铜板
            $table->timestamps();
        });
        
        /**
         * 用户提现记录表
         */
        Schema::create('user_withdraw', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('used'); // 消耗多少积分或者币
            $table->integer('rate', false, true)->default(0); // 兑换率
            $table->decimal('cash'); // 提取铜板
            $table->integer('user_id', false, true); // 提取用户
            $table->integer('admin_id', false, true); // 处理的管理员
            $table->tinyInteger('status', false, true)->default(0); // 处理状态
            $table->string('remark')->nullable(); // 备注信息
            $table->string('user_name')->nullable(); // 提现用户名
            $table->string('bank_name')->nullable(); // 银行类型 如果是支付宝 写写支付宝
            $table->string('bank_account')->nullable(); // 银行账号
            $table->timestamps();
        });
        
        /**
         * 用户银行卡信息
         */
        Schema::create('user_bank', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->string('bank_name');
            $table->string('bank_code');
            $table->string('bank_account');
            $table->string('account_name'); // 银行账号实名认证的信息
            $table->tinyInteger('is_default', false, true)->default(0);
            $table->timestamps();
        });
        
        /**
         * 用户表修改
         */
        Schema::table('users', function (Blueprint $table) {
            $table->string('mobile', 30); // 手机号码
            $table->string('avatar', 255)->nullable(); // 头像
            $table->string('nickname', 50)->nullable(); // 昵称
            $table->tinyInteger('gender', false, true)->default(0); // 性别
            $table->integer('birth_day', false, true)->default(0); // 出生年月
            $table->smallInteger('career_id', false, true)->default(0); // 职业类型
            $table->smallInteger('degree', false, true)->default(0); // 学历
            $table->string('city', 255)->nullable(); // 城市信息
            $table->string('address', 255)->nullable(); // 现居地
            $table->decimal('cash_recharge')->default(0); // 充值账户
            $table->decimal('cash_reward')->default(0); // 报酬账号
            $table->decimal('cash_frozen')->default(0); // 报酬账号
            
            $table->string('invite_code', 40)->nullable();
            $table->string('invite_by', 40)->nullable();
            
            $table->integer('last_update', false, true)->default(0); // 最新一次完善所有信息的时间
            $table->integer('register_ip', false, true);
            $table->integer('last_login_time', false, true);
            $table->integer('last_login_ip', false, true);
            
            $table->timestamp('delete_at')->nullable();
        });
        
        /**
         * 用户职业表
         */
        Schema::create('sys_careers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->smallInteger('sort', false, true)->default(0);
            $table->tinyInteger('display', false, true)->default(1);
            $table->timestamps();
        });
        
        /**
         * 用户学历表
         */
        Schema::create('sys_degrees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->smallInteger('sort', false, true)->default(0);
            $table->tinyInteger('display', false, true)->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adm_category');
        Schema::dropIfExists('adm_posts');
        Schema::dropIfExists('adm_post_click_log');
        Schema::dropIfExists('adm_user_currency');
        Schema::dropIfExists('adm_user_cash');
        Schema::dropIfExists('user_withdraw');
        Schema::dropIfExists('user_bank');
        // Schema::dropIfExists('users');
        Schema::dropIfExists('sys_careers');
        Schema::dropIfExists('sys_degrees');
    }
}
