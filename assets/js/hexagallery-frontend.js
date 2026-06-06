(function ($) {
  "use strict";

  class HexaGallery {
    constructor($element) {
      this.$element = $element;
      this.$wrapper = $element.find(".gallery-wrapper");
      this.$grid = $element.find(".hex-grid");
      
      // Correctly read data attributes from the wrapper
      this.showTitle = this.$wrapper.attr("data-show-lb-title") === "yes";
      this.showDesc = this.$wrapper.attr("data-show-lb-desc") === "yes";
      
      this.items = [];
      this.currentIndex = 0;
      this.overlay = null;

      this.init();
    }

    init() {
      const self = this;
      
      // Collect all lightbox items
      this.$grid.find(".hex.has-lightbox").each(function (index) {
        const $item = $(this);
        self.items.push({
          index: index,
          src: $item.data("lb-src"),
          title: $item.data("lb-title"),
          desc: $item.data("lb-desc"),
          $el: $item
        });

        // Click event
        $item.on("click", function (e) {
          e.preventDefault();
          self.openLightbox(index);
        });

        // Accessibility: Enter key
        $item.on("keydown", function (e) {
          if (e.key === "Enter") {
            self.openLightbox(index);
          }
        });
      });
    }

    openLightbox(index) {
      this.currentIndex = index;
      this.createOverlay();
      this.updateLightboxContent();
      
      setTimeout(() => {
        this.overlay.classList.add("active");
        document.body.style.overflow = "hidden";
      }, 10);

      this.attachEvents();
    }

    createOverlay() {
      if (this.overlay) return;

      const overlay = document.createElement("div");
      overlay.className = "hex-lightbox-overlay";
      overlay.innerHTML = `
        <div class="hex-lightbox-container">
          <button class="hex-lightbox-close" aria-label="Close">&times;</button>
          <button class="hex-lightbox-nav hex-lightbox-prev" aria-label="Previous">&lsaquo;</button>
          <div class="hex-lightbox-image-wrapper">
            <img src="" alt="" class="hex-lightbox-image">
          </div>
          <button class="hex-lightbox-nav hex-lightbox-next" aria-label="Next">&rsaquo;</button>
          <div class="hex-lightbox-info">
            <h2 class="hex-lightbox-title"></h2>
            <p class="hex-lightbox-desc"></p>
          </div>
        </div>
      `;

      document.body.appendChild(overlay);
      this.overlay = overlay;

      // Close events
      this.overlay.addEventListener("click", (e) => {
        if (e.target === this.overlay || e.target.classList.contains("hex-lightbox-close") || e.target.classList.contains("hex-lightbox-container")) {
          this.closeLightbox();
        }
      });

      // Nav events
      this.overlay.querySelector(".hex-lightbox-prev").addEventListener("click", (e) => {
        e.stopPropagation();
        this.prev();
      });

      this.overlay.querySelector(".hex-lightbox-next").addEventListener("click", (e) => {
        e.stopPropagation();
        this.next();
      });
    }

    updateLightboxContent() {
      const item = this.items[this.currentIndex];
      const img = this.overlay.querySelector(".hex-lightbox-image");
      const title = this.overlay.querySelector(".hex-lightbox-title");
      const desc = this.overlay.querySelector(".hex-lightbox-desc");
      const info = this.overlay.querySelector(".hex-lightbox-info");

      // Set content
      img.src = item.src;
      
      if (this.showTitle && item.title) {
        title.textContent = item.title;
        title.style.display = "block";
      } else {
        title.style.display = "none";
      }

      if (this.showDesc && item.desc) {
        desc.textContent = item.desc;
        desc.style.display = "block";
      } else {
        desc.style.display = "none";
      }

      // Hide info container if both are disabled
      if (!this.showTitle && !this.showDesc) {
        info.style.display = "none";
      } else {
        info.style.display = "block";
      }

      // Hide/Show nav buttons based on length
      const prevBtn = this.overlay.querySelector(".hex-lightbox-prev");
      const nextBtn = this.overlay.querySelector(".hex-lightbox-next");

      if (this.items.length <= 1) {
        prevBtn.style.display = "none";
        nextBtn.style.display = "none";
      } else {
        prevBtn.style.display = "block";
        nextBtn.style.display = "block";
      }
    }

    prev() {
      this.currentIndex = (this.currentIndex - 1 + this.items.length) % this.items.length;
      this.updateLightboxContent();
    }

    next() {
      this.currentIndex = (this.currentIndex + 1) % this.items.length;
      this.updateLightboxContent();
    }

    closeLightbox() {
      if (!this.overlay) return;
      this.overlay.classList.remove("active");
      document.body.style.overflow = "";
      
      setTimeout(() => {
        if (this.overlay) {
          this.overlay.remove();
          this.overlay = null;
        }
      }, 400);

      this.detachEvents();
    }

    attachEvents() {
      this.handleKeyDown = (e) => {
        if (e.key === "Escape") this.closeLightbox();
        if (e.key === "ArrowLeft") this.prev();
        if (e.key === "ArrowRight") this.next();
      };
      window.addEventListener("keydown", this.handleKeyDown);
    }

    detachEvents() {
      window.removeEventListener("keydown", this.handleKeyDown);
    }
  }

  // Hook into Elementor
  $(window).on("elementor/frontend/init", function () {
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/hexagallery.default",
      function ($scope) {
        new HexaGallery($scope);
      }
    );
  });
})(jQuery);
