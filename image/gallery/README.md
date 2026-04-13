# Gallery Images Guide

## How to Customize Your Gallery

The gallery section is now ready for your photos! Follow these steps to add your images:

### 📁 Required Image Files

Add your images to this folder (`image/gallery/`) with the following names:

1. **tournament1.jpg** - Football Tournament image
2. **tournament2.jpg** - Badminton Championship image
3. **tournament3.jpg** - Tennis Tournament image
4. **training1.jpg** - Cricket Training image
5. **training2.jpg** - Volleyball Training image
6. **training3.jpg** - Athletics Training image
7. **event1.jpg** - Annual Sports Day image
8. **event2.jpg** - Awards Ceremony image

### 📋 Image Specifications

- **Format**: JPG, PNG, or WEBP
- **Size**: 600x600px recommended (larger is fine, will auto-scale)
- **File Size**: Keep under 500KB for faster loading

### ✏️ Customize Gallery Content

To customize the gallery titles and descriptions, edit the `index.html` file and look for the Gallery Section (around line 285):

```html
<!-- Gallery Item 1 -->
<div class="gallery-item" data-category="tournaments">
    <div class="gallery-image">
        <img src="image/gallery/tournament1.jpg" alt="Football Tournament">
        <div class="gallery-overlay">
            <h3>Your Title Here</h3>
            <p>Your Description Here</p>
        </div>
    </div>
</div>
```

**Edit:**
- `data-category` - Change to: tournaments, training, events, or all
- `alt="..."` - Brief description of the image
- `<h3>...</h3>` - Title shown on hover
- `<p>...</p>` - Description shown on hover

### 🎨 Adding More Gallery Items

To add more photos, duplicate the gallery item code and customize:

```html
<!-- Gallery Item 9 -->
<div class="gallery-item" data-category="tournaments">
    <div class="gallery-image">
        <img src="image/gallery/tournament4.jpg" alt="New Tournament">
        <div class="gallery-overlay">
            <h3>Your New Title</h3>
            <p>Your New Description</p>
        </div>
    </div>
</div>
```

### 📌 Filter Categories

The gallery supports 4 filter categories:
- **tournaments** - Competitive events
- **training** - Practice sessions
- **events** - Special activities
- **all** - Shows all images

### 🎯 Tips

- Use square or nearly square images (aspect ratio 1:1) for best appearance
- Ensure good lighting and compose pictures well
- Compress images to reduce file size before uploading
- Keep consistent style/theme across all gallery images
- Update image alt text for better accessibility

Enjoy your gallery! 🎉
