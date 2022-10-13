<?php

namespace Daaner\NovaPoshta\Models;

use Daaner\NovaPoshta\NovaPoshta;
use Daaner\NovaPoshta\Traits\AdditionalServiceProperty;
use Daaner\NovaPoshta\Traits\InternetDocumentProperty;

class AdditionalService extends NovaPoshta
{
    protected $model = 'AdditionalService';
    protected $calledMethod;
    protected $methodProperties = [];

    use InternetDocumentProperty, AdditionalServiceProperty;

    /**
     * Проверка возможности создания заявки на возврат.
     *
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/a778f519-8512-11ec-8ced-005056b2dbe1
     *
     * @param  string  $ttn
     * @return array
     */
    public function CheckPossibilityCreateReturn(string $ttn): array
    {
        $this->calledMethod = 'CheckPossibilityCreateReturn';
        $this->methodProperties['Number'] = $ttn;

        return $this->getResponse($this->model, $this->calledMethod, $this->methodProperties);
    }

    /**
     * Проверка возможности создания заявки на переадресацию отправки.
     *
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/a8d29fc2-8512-11ec-8ced-005056b2dbe1
     *
     * @param  string  $ttn
     * @return array
     */
    public function checkPossibilityForRedirecting(string $ttn): array
    {
        $this->calledMethod = 'checkPossibilityForRedirecting';
        $this->methodProperties['Number'] = $ttn;

        return $this->getResponse($this->model, $this->calledMethod, $this->methodProperties);
    }

    /**
     * Получение списка причин возврата.
     *
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/a7a6bacb-8512-11ec-8ced-005056b2dbe1
     *
     * @return array
     */
    public function getReturnReasons(): array
    {
        $this->calledMethod = 'getReturnReasons';
        $this->methodProperties = null;

        return $this->getResponse($this->model, $this->calledMethod, $this->methodProperties);
    }

    /**
     * Получение списка подтипов причины возврата.
     *
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/a7cb69ee-8512-11ec-8ced-005056b2dbe1
     *
     * @param  string|null  $ref
     * @return array
     */
    public function getReturnReasonsSubtypes(?string $ref = null): array
    {
        $this->calledMethod = 'getReturnReasonsSubtypes';
        $this->methodProperties['ReasonRef'] = $ref ?: config('novaposhta.ref_return_reasons');

        return $this->getResponse($this->model, $this->calledMethod, $this->methodProperties);
    }

    /**
     * Получение списка заявок на возврат.
     *
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/a7cb69ee-8512-11ec-8ced-005056b2dbe1
     *
     * @return array
     */
    public function getReturnOrdersList(): array
    {
        $this->calledMethod = 'getReturnOrdersList';
        $this->methodProperties = null;

        return $this->getResponse($this->model, $this->calledMethod, $this->methodProperties);
    }


    /**
     * Создание заявки на возврат.
     *
     * Возврат на адрес отправителя.
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/a7fb4a3a-8512-11ec-8ced-005056b2dbe1
     *
     * Возврат на новый адрес отделения.
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/5a64f960-e7fa-11ec-a60f-48df37b921db
     *
     * Возврат на новый адрес по адресной доставке.
     * @see https://developers.novaposhta.ua/view/model/a7682c1a-8512-11ec-8ced-005056b2dbe1/method/175baec3-8f0d-11ec-8ced-005056b2dbe1
     *
     * @param string $ttn
     * @param string|null $type
     * @return array
     */
    public function save(string $ttn, ?string $type = null): array
    {
        $this->calledMethod = 'save';

        $this->methodProperties['IntDocNumber'] = $ttn;
        $this->methodProperties['OrderType'] = 'orderCargoReturn';
        $this->getPaymentMethod();
        $this->getReason();
        $this->getSubtypeReason();
        $this->getNote();

        if (! $this->Note) {
            $this->methodProperties['Note'] = config('novaposhta.return_note');
        }

        /**
         * Возврат на адрес отправления
         */
        $this->getReturnAddressRef();

        /**
         * Возврат на новый адрес отделения
         */
        $this->getRecipientWarehouse();

        /**
         * Возврат на новый адрес по адресной доставке
         */
        $this->getRecipientSettlement();

        return $this->getResponse($this->model, $this->calledMethod, $this->methodProperties);
    }
}
