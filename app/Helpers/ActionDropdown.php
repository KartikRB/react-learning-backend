<?php
namespace App\Helpers;

class ActionDropdown
{
    protected $actions = [];
    protected $formActions = [];
    protected $ajaxActions = [];
    protected $iconClassPrefix = 'fs-3 ti ti-';

    public function __construct() {}

    public function addAction($name, $icon, $link, $class = '', $attrs = [])
    {
        $this->actions[] = [
            'name' => $name,
            'icon' => $icon,
            'link' => $link,
            'class' => $class,
            'attrs' => $attrs,
        ];
        return $this;
    }

    public function addAjaxAction($deleteTitle, $name, $icon, $link, $id, $class = '')
    {
        $this->ajaxActions[] = [
            'name' => $name,
            'icon' => $icon,
            'link' => $link,
            'class' => $class,
            'id' => $id,
            'deleteTitle' => $deleteTitle,
        ];
        return $this;
    }

    public function addFormAction($deleteTitle, $name, $icon, $link, $id, $class = '', $actionClass = 'delete-action', $method='DELETE')
    {
        $this->formActions[] = [
            'name' => $name,
            'icon' => $icon,
            'link' => $link,
            'id' => $id,
            'class' => $class,
            'method' => $method,
            'actionClass' => $actionClass,
            'deleteTitle' => $deleteTitle,
        ];
        return $this;
    }

    public function render()
    {
        $html = '<ul class="action-btn d-flex align-items-center m-0 p-0 list-unstyled">';

        foreach ($this->actions as $action) {
            $attrString = '';
            if (!empty($action['attrs'])) {
                foreach ($action['attrs'] as $key => $value) {
                    $attrString .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
                }
            }
            $html .= '<li>';
            $html .= '<a href="' . htmlspecialchars($action['link']) . '" '
                . 'class="dropdown-item d-flex align-items-center gap-3 ' . htmlspecialchars($action['class']) . '"'
                . $attrString . '>';
            $html .= '<i class="' . $this->iconClassPrefix . htmlspecialchars($action['icon']) . '"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        foreach ($this->ajaxActions as $action) {
            $html .= '<li>';
            $html .= '<a href="javascript:void(0)" class="dropdown-item d-flex align-items-center gap-3 ' . htmlspecialchars($action['class']) . '" data-delete-title="Are you sure you want to delete ' . htmlspecialchars($action['deleteTitle']) . '." data-url="' . htmlspecialchars($action['link']) . '" data-id="' . htmlspecialchars($action['id']) . '">';
            $html .= '<i class="' . $this->iconClassPrefix . htmlspecialchars($action['icon']) . '"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }

        foreach ($this->formActions as $action) {
            $html .= '<li>';
            $html .= '<a href="javascript:void(0)" '
                . 'class="dropdown-item d-flex align-items-center gap-3 ' . htmlspecialchars($action['actionClass']) . '" '
                . 'data-url="' . htmlspecialchars($action['link']) . '" '
                . 'data-id="' . htmlspecialchars($action['id']) . '" '
                . 'data-delete-title="' . htmlspecialchars($action['deleteTitle']) . '" '
                . 'data-class="' . htmlspecialchars($action['class']) . '" '
                . 'title="' . htmlspecialchars($action['name']) . '">';
            $html .= '<form id="' . htmlspecialchars($action['class']) . '-form-' . htmlspecialchars($action['id']) . '" '
                . 'action="' . htmlspecialchars($action['link']) . '" method="POST" style="display:none;">';
            $html .= csrf_field();
            $html .= method_field(htmlspecialchars($action['method']));
            $html .= '</form>';
            $html .= '<i class="' . $this->iconClassPrefix . htmlspecialchars($action['icon']) . '"></i>';
            $html .= '</a>';
            $html .= '</li>';
        }


        $html .= '</ul>';
        return $html;
    }

    public static function create()
    {
        return new self();
    }
}
