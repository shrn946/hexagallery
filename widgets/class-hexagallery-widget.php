<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;

class HexaGallery_Widget extends Widget_Base {

	public function get_name() {
		return 'hexagallery';
	}

	public function get_title() {
		return esc_html__( 'HexaGallery', 'hexagallery' );
	}

	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	public function get_keywords() {
		return [ 'hexagon', 'gallery', 'grid', 'image', 'hexa' ];
	}

	public function get_script_depends() {
		return [ 'hexagallery-frontend' ];
	}

	public function get_style_depends() {
		return [ 'hexagallery-frontend' ];
	}

	protected function register_controls() {

		// --- Content Tab ---
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Gallery Items', 'hexagallery' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			[
				'label'   => esc_html__( 'Choose Image', 'hexagallery' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'hexagallery' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'New Hexagon Item', 'hexagallery' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'description',
			[
				'label'   => esc_html__( 'Sub Description', 'hexagallery' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Portfolio item description', 'hexagallery' ),
			]
		);

		$repeater->add_control(
			'lightbox_title',
			[
				'label'   => esc_html__( 'Lightbox Title', 'hexagallery' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Project Overview', 'hexagallery' ),
			]
		);

		$repeater->add_control(
			'lightbox_description',
			[
				'label'   => esc_html__( 'Lightbox Sub Description', 'hexagallery' ),
				'type'    => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Detailed project insights and high-resolution view.', 'hexagallery' ),
			]
		);

		$this->add_control(
			'gallery_items',
			[
				'label'       => esc_html__( 'Hexagons', 'hexagallery' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [],
				'title_field' => '{{{ title }}}',
			]
		);

		$this->end_controls_section();

		// --- Style Tab: Images ---
		$this->start_controls_section(
			'section_style_images',
			[
				'label' => esc_html__( 'Images & Effects', 'hexagallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'grayscale_amount',
			[
				'label'      => esc_html__( 'Grayscale Amount (%)', 'hexagallery' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 100 ],
				],
				'default'    => [ 'size' => 70 ],
				'selectors'  => [
					'{{WRAPPER}} .hex img' => 'filter: grayscale({{SIZE}}%) brightness(var(--hex-brightness)) contrast(1.1);',
				],
				'condition' => [
					'enable_grayscale' => 'yes',
				],
			]
		);

		$this->add_control(
			'brightness_amount',
			[
				'label'      => esc_html__( 'Brightness', 'hexagallery' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 2, 'step' => 0.1 ],
				],
				'default'    => [ 'size' => 0.7 ],
				'selectors'  => [
					'{{WRAPPER}} .hex' => '--hex-brightness: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'hover_zoom',
			[
				'label'      => esc_html__( 'Hover Zoom Scale', 'hexagallery' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [ 'min' => 1, 'max' => 1.5, 'step' => 0.05 ],
				],
				'default'    => [ 'size' => 1.15 ],
				'selectors'  => [
					'{{WRAPPER}} .hex' => '--hover-scale: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'hex_shadow',
				'selector' => '{{WRAPPER}} .hex:hover, {{WRAPPER}} .hex:focus-within',
			]
		);

		$this->end_controls_section();

		// --- Style Tab: Caption ---
		$this->start_controls_section(
			'section_style_caption',
			[
				'label' => esc_html__( 'Caption Overlay', 'hexagallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'caption_bg',
			[
				'label'     => esc_html__( 'Background Gradient', 'hexagallery' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .hex-caption' => 'background: linear-gradient(to top, {{VALUE}} 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 100%);',
				],
			]
		);

		$this->add_control(
			'caption_padding',
			[
				'label'      => esc_html__( 'Padding Bottom', 'hexagallery' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [ 'min' => 0, 'max' => 150 ],
				],
				'default'    => [ 'unit' => 'px', 'size' => 70 ],
				'selectors'  => [
					'{{WRAPPER}} .hex-caption' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'hexagallery' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .hex-caption h3' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .hex-caption h3',
			]
		);

		$this->add_control(
			'desc_color',
			[
				'label'     => esc_html__( 'Description Color', 'hexagallery' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(255, 255, 255, 0.7)',
				'selectors' => [
					'{{WRAPPER}} .hex-caption p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'desc_typography',
				'selector' => '{{WRAPPER}} .hex-caption p',
			]
		);

		$this->end_controls_section();

		// --- Style Tab: Lightbox ---
		$this->start_controls_section(
			'section_style_lightbox',
			[
				'label' => esc_html__( 'Lightbox', 'hexagallery' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'enable_lightbox' => 'yes',
				],
			]
		);

		$this->add_control(
			'lightbox_overlay_bg',
			[
				'label'     => esc_html__( 'Overlay Background', 'hexagallery' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0, 0, 0, 0.9)',
				'selectors' => [
					'.hex-lightbox-overlay' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lightbox_title_color',
			[
				'label'     => esc_html__( 'Title Color', 'hexagallery' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'.hex-lightbox-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_title_typography',
				'selector' => '.hex-lightbox-title',
			]
		);

		$this->add_control(
			'lightbox_desc_color',
			[
				'label'     => esc_html__( 'Description Color', 'hexagallery' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#cccccc',
				'selectors' => [
					'.hex-lightbox-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lightbox_desc_typography',
				'selector' => '.hex-lightbox-desc',
			]
		);

		$this->end_controls_section();

		// --- Advanced/Settings Tab ---
		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Advanced Settings', 'hexagallery' ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$this->add_control(
			'enable_hover_caption',
			[
				'label'        => esc_html__( 'Enable Hover Caption', 'hexagallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hexagallery' ),
				'label_off'    => esc_html__( 'No', 'hexagallery' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'enable_grayscale',
			[
				'label'        => esc_html__( 'Enable Grayscale Effect', 'hexagallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hexagallery' ),
				'label_off'    => esc_html__( 'No', 'hexagallery' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'enable_lightbox',
			[
				'label'        => esc_html__( 'Enable Lightbox', 'hexagallery' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'hexagallery' ),
				'label_off'    => esc_html__( 'No', 'hexagallery' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'animation_speed',
			[
				'label'      => esc_html__( 'Animation Speed (ms)', 'hexagallery' ),
				'type'       => Controls_Manager::NUMBER,
				'min'        => 100,
				'max'        => 2000,
				'step'       => 50,
				'default'    => 500,
				'selectors'  => [
					'{{WRAPPER}} .hex, {{WRAPPER}} .hex img, {{WRAPPER}} .hex-caption' => 'transition-duration: {{VALUE}}ms;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$items = $settings['gallery_items'];

		if ( empty( $items ) ) {
			return;
		}

		$enable_lightbox = $settings['enable_lightbox'] === 'yes';
		$enable_caption = $settings['enable_hover_caption'] === 'yes';
		$enable_grayscale = $settings['enable_grayscale'] === 'yes';

		$grid_class = 'hex-grid';
		if ( ! $enable_grayscale ) {
			$grid_class .= ' no-grayscale';
		}
		?>
		<div class="gallery-wrapper">
			<div class="<?php echo esc_attr( $grid_class ); ?>">
				<?php foreach ( $items as $index => $item ) : 
					$image_url = $item['image']['url'];
					$image_id = $item['image']['id'];
					$alt = ! empty( $item['image']['alt'] ) ? $item['image']['alt'] : $item['title'];
					
					$item_class = 'hex';
					if ( $enable_lightbox ) {
						$item_class .= ' has-lightbox';
					}

					// Lightbox data attributes
					$lb_data = '';
					if ( $enable_lightbox ) {
						$lb_data = sprintf(
							'data-lb-src="%s" data-lb-title="%s" data-lb-desc="%s"',
							esc_url( $image_url ),
							esc_attr( $item['lightbox_title'] ),
							esc_attr( $item['lightbox_description'] )
						);
					}
				?>
				<div class="<?php echo esc_attr( $item_class ); ?>" tabindex="0" role="button" <?php echo $lb_data; ?>>
					<div class="hex-shape">
						<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_html( $item, 'image', 'image' ); ?>
						<?php if ( $enable_caption ) : ?>
						<div class="hex-caption">
							<h3><?php echo esc_html( $item['title'] ); ?></h3>
							<p><?php echo esc_html( $item['description'] ); ?></p>
						</div>
						<?php endif; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {
		?>
		<#
		var enable_lightbox = settings.enable_lightbox === 'yes';
		var enable_caption = settings.enable_hover_caption === 'yes';
		var enable_grayscale = settings.enable_grayscale === 'yes';

		var grid_class = 'hex-grid';
		if ( ! enable_grayscale ) {
			grid_class += ' no-grayscale';
		}
		#>
		<div class="gallery-wrapper">
			<div class="{{ grid_class }}">
				<# _.each( settings.gallery_items, function( item, index ) { 
					var image_url = item.image.url;
					
					var item_class = 'hex';
					if ( enable_lightbox ) {
						item_class += ' has-lightbox';
					}
				#>
				<div class="{{ item_class }}" tabindex="0" role="button">
					<div class="hex-shape">
						<# if ( image_url ) { #>
							<img src="{{ image_url }}" />
						<# } #>
						<# if ( enable_caption ) { #>
						<div class="hex-caption">
							<h3>{{{ item.title }}}</h3>
							<p>{{{ item.description }}}</p>
						</div>
						<# } #>
					</div>
				</div>
				<# } ); #>
			</div>
		</div>
		<?php
	}
}
