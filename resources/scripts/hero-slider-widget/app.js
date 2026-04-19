import EmblaCarousel from 'embla-carousel';
import Autoplay from 'embla-carousel-autoplay';
import Fade from 'embla-carousel-fade';
import '../../styles/hero-slider-widget/style.scss';

/**
 * Initialize a single Hero Slider instance.
 *
 * @param {HTMLElement} node - The .iar-hero-slider element.
 */
function initHeroSlider( node ) {
    // Destroy previous instance to prevent listener accumulation in the editor
    if ( node._emblaInstance ) {
        node._emblaInstance.destroy();
    }

    const effect   = node.dataset.effect || 'slide';
    const loop     = node.dataset.loop === 'true';
    const autoplay = node.dataset.autoplay === 'true';
    const delay    = parseInt( node.dataset.delay, 10 ) || 5000;
    const duration = parseInt( node.dataset.duration, 10 ) || 600;

    const options = {
        loop,
        align: 'start',
        // Convert ms to Embla weight value (lower = faster). Roughly ms / 20.
        duration: Math.round( duration / 20 ),
    };

    const plugins = [];

    if ( effect === 'fade' ) {
        options.loop      = true;
        options.watchDrag = false;
        plugins.push( Fade() );
    }

    if ( effect === 'parallax' ) {
        options.loop = true;
    }

    if ( autoplay ) {
        plugins.push(
            Autoplay( { delay, stopOnInteraction: false, stopOnMouseEnter: true } )
        );
    }

    const embla = EmblaCarousel( node, options, plugins );

    // --- Parallax: offset background-position based on scroll progress ---
    if ( effect === 'parallax' ) {
        const slides = node.querySelectorAll( '.iar-hero-slider__slide' );
        const PARALLAX_FACTOR = 1.2;
        const MAX_OFFSET     = 15; // matches the extra 30% width (±15%)

        const applyParallax = () => {
            const scrollSnaps    = embla.scrollSnapList();
            const scrollProgress = embla.scrollProgress();

            slides.forEach( ( slide, index ) => {
                let diff = scrollSnaps[ index ] - scrollProgress;

                // Normalize for loop: take the shortest path around the wrap
                if ( diff > 0.5 )  diff -= 1;
                if ( diff < -0.5 ) diff += 1;

                const raw    = diff * PARALLAX_FACTOR * 100;
                const offset = Math.max( -MAX_OFFSET, Math.min( MAX_OFFSET, raw ) );
                slide.style.backgroundPosition = `calc(50% + ${ offset }%) center`;
            } );
        };

        embla.on( 'scroll', applyParallax );
        embla.on( 'init', applyParallax );
        embla.on( 'reInit', applyParallax );
    }

    // --- Arrow navigation ---
    const prevBtn = node.querySelector( '.iar-hero-slider__arrow--prev' );
    const nextBtn = node.querySelector( '.iar-hero-slider__arrow--next' );

    if ( prevBtn ) {
        prevBtn.addEventListener( 'click', () => embla.scrollPrev() );
    }
    if ( nextBtn ) {
        nextBtn.addEventListener( 'click', () => embla.scrollNext() );
    }

    // Dot navigation removed — functionality handled elsewhere or no longer required.

    // --- Content fade-in on slide change ---
    const allContents = node.querySelectorAll( '.iar-hero-slider__content' );

    const activateContent = () => {
        const selected = embla.selectedScrollSnap();
        allContents.forEach( ( content, index ) => {
            if ( index === selected ) {
                // Remove and re-add to restart animation
                content.classList.remove( 'iar-hero-slider__content--active' );
                // Force reflow to restart CSS animation
                void content.offsetWidth;
                content.classList.add( 'iar-hero-slider__content--active' );
            } else {
                content.classList.remove( 'iar-hero-slider__content--active' );
            }
        } );
    };

    embla.on( 'init', activateContent );
    embla.on( 'select', activateContent );

    // Cache instance for cleanup on re-init
    node._emblaInstance = embla;
}

/**
 * Initialize all Hero Slider instances on the page.
 */
function initAll() {
    document.querySelectorAll( '.iar-hero-slider' ).forEach( initHeroSlider );
}

// Init on DOM ready
if ( document.readyState === 'loading' ) {
    document.addEventListener( 'DOMContentLoaded', initAll );
} else {
    initAll();
}

// Re-init in Elementor editor preview
if ( typeof window.elementorFrontend !== 'undefined' && elementorFrontend.hooks ) {
    elementorFrontend.hooks.addAction(
        'frontend/element_ready/iar_hero_slider.default',
        ( $scope ) => {
            const el = $scope[0] || $scope;
            const slider = el.querySelector( '.iar-hero-slider' );
            if ( slider ) {
                initHeroSlider( slider );
            }
        }
    );
}
