<?php
declare(strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\model\UserCoin as UserCoinModel;

class Income extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('income')
            ->setDescription('the income command');
    }

    protected function execute(Input $input, Output $output)
    {
        // 指令输出
        $output->writeln('income');

        while (true) {
            // 获取需要结算的用户币种记录
            $userCoins = UserCoinModel::where('finish_time', '<=', date('Y-m-d H:i:s'))->select();

            foreach ($userCoins as $user_coin) {
                // 进行收益结算逻辑，更新用户收益等信息
                $userCoin = new UserCoinModel();
                $userCoin->freeze($user_coin);
            }
            $output->writeln('监听中');

            sleep(1*60*10);     //每10min自动更新

        }
    }
}
