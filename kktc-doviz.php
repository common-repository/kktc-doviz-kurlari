<?php
/*
  Plugin Name: KKTC Döviz Kurları
  Plugin URI: http://cengizonkal.blogspot.com/
  Description: KKTCMB Resmi Döviz Kurları
  Author: Cengiz Önkal
  Author URI: http://cengizonkal.blogspot.com/
  Version: 1.0.0
 */

class Kktc_doviz_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
                'kktc_doviz_widget', // Base ID
                __('Döviz Kurları', 'text_domain'), // Name
                array('description' => __('KKTCMB Resmi Döviz Kurları', 'text_domain'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $title = apply_filters('widget_title', $instance['title']);

        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        $xml = simplexml_load_file("http://www.kktcmerkezbankasi.org/kur/gunluk.xml");
        echo '<table>';
        echo ' <thead>
          <tr>
            <th>#</th>
            <th>Alış</th>
            <th>Satış</th>
          </tr>
        </thead>';
        echo ' <tbody>';
        foreach ($xml->Resmi_Kurlar->Resmi_Kur as $resmi_kur) {

            echo '<tr>
            <td>' . $resmi_kur->Sembol . '</td>
            <td>' . number_format((float) $resmi_kur->Doviz_Alis, 2, '.', ',') . ' TL</td>
            <td>' . number_format((float) $resmi_kur->Doviz_Satis, 2, '.', ',') . ' TL</td>
            
          </tr>';
        }
        echo '</table>';




        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Başlık', 'text_domain');
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';

        return $instance;
    }

}

// class kktc_doviz_Widget
// register kktc_doviz_Widget widget
function register_kktc_doviz_widget() {
    register_widget('kktc_doviz_Widget');
}

add_action('widgets_init', 'register_kktc_doviz_widget');
