<?php

namespace Daaner\NovaPoshta\Traits;

use Daaner\NovaPoshta\Exceptions\MissedRequiredParam;
use Exception;
use Illuminate\Support\Arr;

trait OptionsSeatProperty
{
    protected $OptionsSeat;
    protected $Weight;

    /**
     * Параметр груза для каждого места отправления.
     * Перебираем значение массива и указываем нужные объемы.
     * Если не указывать значение из конфига в 1 кг.
     *
     * @param string|array $OptionsSeat Указание объемного веса массивом или индексом из массива в конфиге
     * @return $this
     */
    public function setOptionsSeat($OptionsSeat): self
    {
        $data = config('novaposhta.options_seat');

        if (is_array($OptionsSeat) === false) {
            $OptionsSeat = explode(',', /** @scrutinizer ignore-type */ $OptionsSeat);
        }

        foreach ($OptionsSeat as $value) {
            try {
                $this->setCustomOptionsSeat($data[$value]);
            } catch (Exception) {
                $this->setCustomOptionsSeat($data[1]);
            }
        }

        return $this;
    }

    /**
     * @throws MissedRequiredParam
     */
    public function setCustomOptionsSeat(array $data): self
    {
        if (!Arr::has($data, [
            'volumetricVolume',
            'volumetricWidth',
            'volumetricLength',
            'volumetricHeight',
            'weight',
        ])) {
            throw new MissedRequiredParam();
        }

        $this->setWeight($data['weight']);

        $this->OptionsSeat[] = $data;

        return $this;
    }

    /**
     * @return void
     */
    public function getOptionsSeat(): void
    {
        if (!$this->OptionsSeat && !$this->Weight) {
            $defaultSeat = [
                'volumetricVolume' => '1',
                'volumetricWidth' => '24',
                'volumetricLength' => '17',
                'volumetricHeight' => '10',
                'weight' => '1',
            ];

            $this->OptionsSeat = $defaultSeat;
        }
        $this->methodProperties['OptionsSeat'] = $this->OptionsSeat;
    }

    /**
     * Устанавливаем вес груза. По умолчанию значение из конфига.
     * Не обязательно, если выставляем OptionsSeat, но это не точно.
     *
     * @param string|float $weight Вес груза
     * @return $this
     */
    public function setWeight(string|float $weight): self
    {
        $this->Weight = (string)$weight;

        return $this;
    }

    /**
     * Установка веса.
     *
     * @return void
     */
    public function getWeight(): void
    {
        $this->methodProperties['Weight'] = $this->Weight ?: config('novaposhta.weight');
    }
}
