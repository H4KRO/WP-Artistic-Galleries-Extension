var artworksArray;

function initArtworksArray(array){
    artworksArray = array;
    if(array.length == 0) {
        option_new();
        document.getElementById('gallery_selector').style.display = 'none';
        document.getElementById('artworks_displayer').innerText = 'Aucune oeuvre';
    }
}

function openModal(id){
    document.getElementById('modal').style.display="block";
    document.getElementById('artworkId').value = id;
    document.getElementById('artworkTitle').value = getArtworkName(id);
    document.getElementById('artworkImage').src = getArtworkUrl(id);
}

function closeModal(){
    document.getElementById('modal').style.display="none";
}

function getArtworkName(id){
    var name = 'Undefined';
    for(var i = 0; i < artworksArray.length; i++){
        if(artworksArray[i]['id'] == id){
            name = artworksArray[i]['artworkName'];
        }
    }
    return name;
}

function getArtworkUrl(id){
    var url = '';
    for(var i = 0; i < artworksArray.length; i++){
        if(artworksArray[i]['id'] == id){
            url = artworksArray[i]['artworkUrl'];
        }
    }
    return url;
}

function option_new(){
    document.getElementById('artworkGallery_choice').style.display = 'none';
    document.getElementById('artworkGallery_new').style.display = 'block';
}

function gallery_selector(galleryName){
    var every_artworks = document.getElementsByClassName('artwork');
    for(var i = 0; i < every_artworks.length; i++){
        if(every_artworks[i].getAttribute('gallery') != galleryName){
            every_artworks[i].style.display = 'none';
        }else{
            every_artworks[i].style.display = 'inline-block';
        }
    }
}

function every_gallery_display(){
    var every_artworks = document.getElementsByClassName('artwork');
    for(var i = 0; i < every_artworks.length; i++){
        every_artworks[i].style.display = 'inline-block';
    }
}

