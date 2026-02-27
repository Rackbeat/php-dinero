<?php

namespace LasseRafn\Dinero\Builders;

use LasseRafn\Dinero\Models\Creditnote;

class CreditnoteBuilder extends Builder
{
    protected $entity = 'sales/creditnotes';
    protected $model = Creditnote::class;




    /**
     * @param $id
     * @param $timestamp
     *
     * @return mixed
     */
    public function book($id, $timestamp)
    {
        return $this->request->fetchEndPoint('post' , 'sales/creditnotes/' . $id . '/book', [
            'json' => [
                'Timestamp' => $timestamp,
            ]
        ]);
    }
}
