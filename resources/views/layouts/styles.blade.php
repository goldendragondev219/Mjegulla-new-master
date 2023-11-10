<style>
  .upload-box {
    display: inline-block;
    position: relative;
    width: 100%;
    height: 200px;
    border: 2px dashed #ccc;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    overflow: hidden;
  }
  
  .upload-text {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    transform: translateY(-50%);
    color: #777;
  }
  
  .preview-image {
    max-width: 100%;
    max-height: 100%;
    display:none;
  }
  
  .remove-preview-button {
    position: absolute;
    top: 0px;
    right: 5px;
    padding: 0;
    font-size: 1.5rem;
    color: #777;
    background-color: transparent;
    border: none;
    outline: none;
    cursor: pointer;
    display:none;
  }
  
  .remove-preview-button:hover {
    color: red;
  }










/* Product images */

/* Custom file input */
.custom-file-input {
    display: none;
}

/* Upload box */
.upload-box1 {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    width: 100%;
    height: 120px;
    border: 2px dashed #ccc;
    border-radius: 5px;
    overflow: hidden;
    position: relative;
}

/* Upload text */
.upload-text1 {
    font-size: 16px;
    color: #777;
}

/* Preview container */
.preview-container1 {
    display: flex;
    flex-wrap: wrap;
    margin-top: 10px;
}

/* Preview image */
.preview-image1 {
    display: block;
    width: 120px;
    height: 120px;
    object-fit: cover;
    margin-right: 10px;
    margin-bottom: 10px;
}

/* Remove preview button */
.remove-preview-button1 {
    position: absolute;
    top: 0;
    right: 0;
    margin: 5px;
    font-size: 20px;
    color: #fff;
    background-color: #c4c4c4;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    line-height: 0;
    cursor: pointer;
    z-index: 1;
}

.preview-item1 {
    position: relative;
}

.remove-preview-button1 {
    position: absolute;
    top: 0;
    right: 0;
}


/* Preview container on edit product */
.preview-container-edit-product {
    display: flex;
    flex-wrap: wrap;
    margin-top: 10px;
}

/* Preview image on edit product */
.preview-image-edit-product {
    display: block;
    width: 120px;
    height: 120px;
    object-fit: cover;
    margin-right: 10px;
    margin-bottom: 10px;
}


/* Remove preview button */
.remove-preview-button-edit-product {
    position: absolute;
    top: 0;
    right: 0;
    margin: 5px;
    font-size: 20px;
    color: #fff;
    background-color: #c4c4c4;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    line-height: 0;
    cursor: pointer;
    z-index: 1;
}

.preview-item-edit-product {
    position: relative;
}

.remove-preview-button-edit-product {
    position: absolute;
    top: 0;
    right: 0;
}

  
</style>