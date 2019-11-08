<?php

/**
 * Menus Model
 *
 */
class Menus extends Abstract_model {

    public $table           = "menus";
    public $pkey            = "menu_id";
    public $alias           = "mn";

    public $fields          = array(
                                'menu_id'       => array('pkey' => true, 'type' => 'int', 'nullable' => true, 'unique' => true, 'display' => 'Menu ID'),
                                'parent_id'     => array('nullable' => true, 'type' => 'int', 'unique' => false, 'display' => 'Menu Parent'),
                                'module_id'     => array('nullable' => false, 'type' => 'int', 'unique' => false, 'display' => 'ID Module'),

                                'menu_title'    => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Title'),
                                'menu_url'      => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'URL'),
                                'menu_icon'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Icon'),
                                'menu_order'    => array('nullable' => false, 'type' => 'str', 'unique' => false, 'display' => 'Order'),
                                'menu_description'     => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Description'),

                                'created_date'  => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Created Date'),
                                'created_by'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Created By'),
                                'updated_date'  => array('nullable' => true, 'type' => 'date', 'unique' => false, 'display' => 'Updated Date'),
                                'updated_by'    => array('nullable' => true, 'type' => 'str', 'unique' => false, 'display' => 'Updated By'),

                            );

    public $selectClause    = "mn.*";
    public $fromClause      = "menus mn";

    public $refs            = array();

    function __construct() {
        parent::__construct();
    }

    function validate() {

        $ci =& get_instance();
        $userdata = $ci->session->userdata;

        if($this->actionType == 'CREATE') {
            //do something
            // example :
            //$this->record['created_date'] = date('Y-m-d');
            //$this->record['updated_date'] = date('Y-m-d');
            $this->db->set('created_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['created_by'] = $userdata['user_name'];
            $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updated_by'] = $userdata['user_name'];

            // $this->record[$this->pkey] = $this->generate_id($this->table, $this->pkey);

            if(empty($this->record['parent_id']))
                unset($this->record['parent_id']);
        }else {
            //do something
            //example:
            //$this->record['updated_date'] = date('Y-m-d');
            //if false please throw new Exception
            $this->db->set('updated_date',"to_date('".date('Y-m-d')."','yyyy-mm-dd')",false);
            $this->record['updated_by'] = $userdata['user_name'];

            if(empty($this->record['parent_id']))
                unset($this->record['parent_id']);
        }
        return true;
    }

    function emptyChildren($menu_id) {
        $sql = "select count(1) as total from menus where parent_id = ?";

        $query = $this->db->query($sql, array($menu_id));
        $row = $query->row_array();

        return $row['total'] == 0;
    }


    function menuItems($module_id) {

        $ci =& get_instance();
        $userdata = $ci->session->userdata;

        $sql = "select a.menu_id,
                (case when b.parent_id is null then 0 else b.parent_id end) as parent_id,
                b.menu_title,
                b.menu_url,
                b.menu_icon,
                b.menu_order
                from role_menu a
                left join menus b on a.menu_id = b.menu_id
                left join role_user c on a.role_id = c.role_id
                where c.user_id = ?
                and b.module_id = ?
                order by b.menu_order asc";

        $query = $this->db->query($sql, array($userdata['user_id'], $module_id));
        return $query->result();
    }


    function htmlMenuSideBar($module_id) {

        $root_id = 0;
        $html = array();
        $items = $this->menuItems($module_id);

        $children = array();
        foreach($items as $item) {
            $children[$item->parent_id][] = (array) $item;
        }

        $loop = !empty( $children[$root_id] );

        // initializing $parent as the root
        $parent = $root_id;
        $parent_stack = array();


        $html[] = '<li class="nav-item start active" data-source="dashboard">
                      <a href="javascript:;" title="Alerts" data-filter-tags="home">
                          <i class="fal fa-home"></i> <span class="nav-link-text" data-i18n="nav.home">Home</span>
                      </a>
                  </li>';
                   
 
        while ( $loop && ( ( $option = $this->myEach( $children[$parent] ) ) || ( $parent > $root_id ) ) )
        {
              if ( $option === false )
              {
                      $parent = array_pop( $parent_stack );

                      // HTML for menu item containing childrens (close)
                      $html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 ) . '</ul>';
                      $html[] = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ) . '</li>';
              }
              elseif ( !empty( $children[$option['value']['menu_id']] ) )
              {
                      $tab = str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 );

                      $icon_parent = 'fal fa-folder';
                      if(!empty($option['value']['menu_icon'])) {
                            $icon_parent = $option['value']['menu_icon'];
                      }
                      // HTML for menu item containing childrens (open)

                      
                      $html[] = sprintf(
                              '%1$s<li class="nav-item">
                                <a href="javascript:;" title="%2$s" data-filter-tags="%7$s">
                                    <i class="fal fa-window"></i>
                                    <i class="%3$s"></i><span class="nav-link-text" data-i18n="nav.%8$s">%4$s</span>
                                </a>',
                              $tab,                                          // %1$s = tabulation
                              $option['value']['menu_title'],                     // %2$s = title
                              $icon_parent,                                  // %3$s = icon
                              $option['value']['menu_title'],   // %4$s = menu
                              strtolower($option['value']['menu_title']),                         //search  
                              str_replace(' ','_',strtolower($option['value']['menu_title']))
                      );
                      $html[] = $tab . "\t" . '<ul>';

                      array_push( $parent_stack, $option['value']['parent_id'] );
                      $parent = $option['value']['menu_id'];
              }
              else {

                      $icon_leaf = 'fal fa-file';
                      if(!empty($option['value']['menu_icon'])) {
                            $icon_leaf = $option['value']['menu_icon'];
                      }

                      // HTML for menu item with no children (aka "leaf")
                     
                      $html[] = sprintf(
                              '%1$s<li class="nav-item"  data-source="%3$s" menu-id="%4$s">
                                  <a href="javascript:;" title="%2$s" data-filter-tags="%7$s">
                                      <i class="%5$s"></i> <span class="nav-link-text" data-i18n="nav.%8$s">%6$s</span>
                                  </a>
                              </li>',
                              str_repeat( "\t", ( count( $parent_stack ) + 1 ) * 2 - 1 ),   // %1$s = tabulation
                              $option['value']['menu_title'],                            // %2$s = title
                              $option['value']['menu_url'],
                              $option['value']['menu_id'],                          // %3$s = url,
                              $icon_leaf,                                           // %4$s = icon,
                              $option['value']['menu_title'],                        // %5$s = menu
                              strtolower($option['value']['menu_title']),                         //search  
                              str_replace(' ','_',strtolower($option['value']['menu_title']))
                      );
              }
        }

        return implode( "\r\n", $html );
    }

    function myEach(&$arr) {
        $key = key($arr);
        $result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
        next($arr);
        return $result;
    }

    function getPrivilegeMenu($role_id, $menu_id)
    {
        $result = array();
        $sql = "select a.app_object_type_id, a.code, b.privilage_id, coalesce(b.is_active, null, 'N') as is_active, b.created_date, b.created_by, b.updated_date, b.updated_by, b.role_id, b.menu_id
                from app_object_type a
                left join privilage b on a.app_object_type_id = b.app_object_type_id
                and b.role_id = ? and b.menu_id = ?
                order by a.app_object_type_id asc";
        $qs = $this->db->query($sql, array($role_id, $menu_id));

        if ($qs->num_rows() > 0) $result = $qs->result();
        return $result;
    }
}

/* End of file Menus.php */