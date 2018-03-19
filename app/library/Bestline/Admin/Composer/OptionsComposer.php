<?php
namespace Bestline\Admin\Composer;

class OptionsComposer
{
    public function compose($view) 
    {
        
        //first check if this is a custom page
        if ($view->page === 'admin.options')
        {
            //add page-specific assets
            $view->js += array(
                'jstree' => '/js/vendor/jsTree/jstree.min.js',
                'options' => '/js/vendor/admin/options.js',
                'bootstrap'=> '/js/vendor/bootstrap.js',
                'jstree-search' => '/js/vendor/jsTree/plugins/jstree.search.js',
                'jstree-contextmenu' => '/js/vendor/jsTree/plugins/jstree.contextmenu.js',
                'jstree-dnd' => '/js/vendor/jsTree/plugins/jstree.dnd.js',
                'jstree-state' => '/js/vendor/jsTree/plugins/jstree.state.js',
                'jstree-types' => '/js/vendor/jsTree/plugins/jstree.types.js',
                'jstree-wholerow' => '/js/vendor/jsTree/plugins/jstree.wholerow.js'
            );

            $view->css += array(
                'jstree' => '/js/vendor/jsTree/themes/default/style.min.css',
                'font-awesome' => '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css',
            );
            
            array_unshift($view->css, '/css/vendor/bootstrap.css');
            
            $view->js['jquery'] = '//code.jquery.com/jquery.js';
            
            unset($view->js['jquery-ui']);
            
        }
    }
}