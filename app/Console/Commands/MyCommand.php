<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class MyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:mycommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Currency from www.cbr.ru';

    const USD = 'USD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $date = date('Y-m-d', time());
        $usd = $this->getCurrencyForDate($date);
        $curr = new Currency();
        $curr->usd = $usd;
        $curr->date = $date;
        $curr->save();

    }

    private function initCurrenciesToDb()
    {
        
        for ($day = 6500; $day > 0; $day--) {
            $currency = new Currency();
            $date = date('Y-m-d', strtotime('-' . $day . ' day'));
            $currency->date = $date;
            $currency->usd = $this->getCurrencyForDate($date);
            $currency->save();
        }
    }

    private function getCurrencyForDate($date)
    {
        $soap = new \SoapClient('http://www.cbr.ru/DailyInfoWebServ/DailyInfo.asmx?WSDL');
        $result = new \SimpleXMLElement($soap->GetCursOnDate(['On_date' => $date])->GetCursOnDateResult->any);

        foreach ($result->ValuteData->ValuteCursOnDate as $valute) {
            if ($valute->VchCode == self::USD) {
                return floatval($valute->Vcurs);
            }
        }
    }
}
