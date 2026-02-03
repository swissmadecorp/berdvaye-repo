@extends ("layouts.default1")  

@section ('header')
<!-- Only add this when it's on catalog page -->
<script src="{{ asset('/public/js/jquery.min.js') }}"></script>
@endsection

@section ('content')

<div class="m_top_75"></div>
<h4 class="text-center">Our Catalog</h4>
<div class="sub-text-line m_bottom_25"><img src="/public/images/title.png"></div>

<section class="alternate">
	<div class="flip-book-container" style="height: 70vh;
        width: 95%;">

  	</div>

</section>
@endsection
@section ('footer')

<script type="text/javascript" src="/public/catalogs/js/html2canvas.min.js"></script>
<script type="text/javascript" src="/public/catalogs/js/three.min.js"></script>
<script type="text/javascript" src="/public/catalogs/js/pdf.min.js"></script>
<script type="text/javascript" src="/public/catalogs/js/3dflipbook.min.js"></script>
@endsection

@section ('jquery')
<script type="text/javascript">
	
	function BerdVayeCallback(n) {
        return {
          type: 'image',
          src: '/public/catalogs/books/image/'+(n+1)+'.jpg',
          interactive: true
        };
      }

      $('.flip-book-container').FlipBook({
        pageCallback: BerdVayeCallback,
		pages: 8,
		controlsProps: {
			actions: {
				cmdBackward: {
				code: 37,
				},
				cmdForward: {
				code: 39
				},
			}
		},
        propertiesCallback: function(props) {
          props.cssLayersLoader = function(n, clb) {// n - page number
            // clb([{
            //   js: function (jContainer) {
            //     console.log(jContainer);
            //     return {
            //       hide: function() {console.log('hide');},
            //       hidden: function() {console.log('hidden');},
            //       show: function() {console.log('show');},
            //       shown: function() {console.log('shown');},
            //       dispose: function() {console.log('dispose');}
            //     };
            //   }
            // }]);
          };
		  props.cover.color = 0x000000;
		  props.sheet.flexibility = 30;
		  props.sheet.wave = 1;
		  props.sheet.shape = 1;

          return props;
        },
        template: {
          html: '/public/catalogs/templates/default-book-view.html',
          styles: [
            '/public/catalogs/css/short-black-book-view.css'
          ],
          links: [
            {
              rel: 'stylesheet',
              href: '/public/catalogs/css/font-awesome.min.css'
            }
          ],
          script: '/public/catalogs/js/default-book-view.js',
          sounds: {
            startFlip: '/public/catalogs/sounds/start-flip.mp3',
            endFlip: '/public/catalogs/sounds/end-flip.mp3'
          }
        }
      });
</script>
@endsection        
