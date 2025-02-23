<?php

namespace App\Livewire\Backstage;

use App\Models\Game;

class GameTable extends TableComponent
{
    public $sortField = 'revealed_at';

    public $extraFilters = 'games-filters';

    public $prizeId = null;

    public $account = null;

    public $status = null;
    public $createdAt = null;
    public $updatedAt = null;
    public $endDate = null;

    public function export() {}

    public function render()
    {
        $columns = [
            [
                'title' => 'account',
                'sort' => true,
            ],
            [
                'title' => 'Prize Name',
                'attribute' => 'prizeName',
                'sort' => true,
            ],
            [
                'title' => 'Status',
                'attribute' => 'status',
                'sort' => true,
                ''
            ],
            [
                'title' => 'Revealed At',
                'attribute' => 'revealed_at',
                'sort' => true,
            ],
        ];

        return view('livewire.backstage.table', [
            'columns' => $columns,
            'resource' => 'games',
            'rows' => Game::selectRaw('*, prizes.name as prizeName')
                ->join('prizes', 'prizes.id', '=', 'games.prize_id')
                ->where('prizes.campaign_id', session('activeCampaign'))
                ->orderBy($this->sortField, $this->sortDesc ? 'DESC' : 'ASC')
                ->paginate($this->perPage),
        ]);
    }
}
