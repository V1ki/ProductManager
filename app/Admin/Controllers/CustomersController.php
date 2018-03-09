<?php

namespace App\Admin\Controllers;

use App\SHAdmin ;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CustomersController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('customers.customers_list'));
            $content->description(trans('customers.customers_list_desc'));

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header(trans('customers.edit_customers'));
            $content->description(trans('customers.edit_customers'));

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header(trans('customers.create_customers'));
            $content->description(trans('customers.create_customers'));

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(SHAdmin::class, function (Grid $grid) {

            $grid->column('customer_id',trans('customers.customer_id'));
            $grid->column('customer_name',trans('customers.customer_name'));
            $grid->column('sh_name_id',trans('customers.sh_name_id'));

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(SHAdmin::class, function (Form $form) {
            $form->display('customer_name',trans('customers.customer_name'));
        });
    }
}
