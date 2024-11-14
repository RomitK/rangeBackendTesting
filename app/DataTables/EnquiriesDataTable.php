<?php

namespace App\DataTables;

use App\Models\Enquiry;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EnquiriesDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filter(function ($query) {
                if (request()->has('filter.q') && !empty(request('filter.q'))) {
                    $searchTerm = request('filter.q');
                    $query->where(function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('email',  $searchTerm)
                            ->orWhere('mobile', 'like', '%' . $searchTerm . '%');

                        $query->orWhere('number_of_rooms', $searchTerm)
                            ->orWhere('min_price', $searchTerm)
                            ->orWhere('max_price', $searchTerm);
                    });
                }
            })
            ->editColumn('mobile', function ($enquiry) {
                return $enquiry->mobile_country_code . ' ' . $enquiry->mobile;
            })
            ->editColumn('property_status', function ($enquiry) {
                return Str::title(Str::replace('_', ' ', $enquiry->property_status));
            })
            ->editColumn('property_type', function ($enquiry) {
                return Str::title(Str::replace('_', ' ', $enquiry->property_type));
            })
            ->editColumn('min_price', function ($enquiry) {
                return number_format($enquiry->min_price);
            })
            ->editColumn('max_price', function ($enquiry) {
                return number_format($enquiry->max_price);
            })
            ->setRowId('id');
    }

    public function query(Enquiry $model): QueryBuilder
    {
        return $model->newQuery()->latest();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('enquiries-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->orderBy(1)
            ->searching(false)
            ->selectStyleSingle();
    }

    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('campaign_id'),
            Column::make('name'),
            Column::make('email'),
            Column::make('mobile'),
            Column::make('property_status'),
            Column::make('property_type'),
            Column::make('number_of_rooms'),
            Column::make('min_price'),
            Column::make('max_price'),
        ];
    }

    protected function filename(): string
    {
        return 'Enquiries_' . date('YmdHis');
    }
}
