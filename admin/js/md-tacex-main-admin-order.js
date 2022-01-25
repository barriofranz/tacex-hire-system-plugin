(function( $ ) {
	'use strict'; 

  console.log('hi from plugin')

  feather.replace()

  const saveOrder = document.querySelector('[name="save"]');
  const metaBox = document.querySelector('#woocommerce-order-checklist');
  let clicked = false;

  if (saveOrder !== null && metaBox !== null ) {
    
    const orderID = metaBox.querySelector('[data-order-id]')
    const listItems = [...metaBox.querySelectorAll('.list_items')]
    const inputFiles = [...metaBox.querySelectorAll('input[type="file"]')]
    const previews = [...metaBox.querySelectorAll('.file_preview')]
    const removeUploads = [...metaBox.querySelectorAll('.remove_btn')]
    const fileThumbs = [...metaBox.querySelectorAll("canvas")]
    const modImgs = [...metaBox.querySelectorAll(".mod_img")]
    const dataUploadURL = metaBox.querySelector('[data-url]')
    const notes = [...metaBox.querySelectorAll('.checklist_note')]

    saveOrder.addEventListener('click', handleOrderSave)
    saveOrder.addEventListener('click', uploadFile)
    
    const arrInputFiles = inputFiles.length > 0 ? inputFiles : 0
    for (let i=0; i<arrInputFiles.length; i++) {
      arrInputFiles[i].style.opacity = 0;

      arrInputFiles[i].addEventListener('change', updateImageDisplay);

      function updateImageDisplay() {
        while(previews[i].firstChild) {
          previews[i].removeChild(previews[i].firstChild);
        }

        const curFiles = arrInputFiles[i].files;
        if (curFiles.length === 0) {
          const para = document.createElement('p');
          para.textContent = 'No files currently selected for upload';
          previews[i].appendChild(para);

        } else {
          const list = document.createElement('ol');
          previews[i].appendChild(list);

          for (const file of curFiles) {
            const listItem = document.createElement('li');
            const para = document.createElement('p');

            if (validFileType(file)) {
              const image = document.createElement('img');
              const ftype = file.type
              const fname = file.name
              const fsize = file.size
              const name = (fname.length > 25) ? `${fname.slice(0, 25)}....${ftype.slice(6, fname.length)}` : fname
              
              para.innerHTML = `File name <strong title="${fname}">${name}</strong>, file size <strong>${returnFileSize(fsize)}</strong>.`;
              image.alt = 'Issue Image'
              image.title = fname
              image.src = URL.createObjectURL(file);

              listItem.appendChild(image);
              listItem.appendChild(para);

            } else {
              para.innerHTML = `File name <strong>${file.name}</strong>: Not a valid file type. Update your selection.`;
              listItem.appendChild(para);
            }

            arrInputFiles[i].previousElementSibling.style.display = 'none'
            list.appendChild(listItem);
          }
        }
      }

      const fileTypes = [
        "image/apng",
        "image/bmp",
        "image/gif",
        "image/jpeg",
        "image/pjpeg",
        "image/png",
        "image/svg+xml",
        "image/tiff",
        "image/webp",
        "image/x-icon"
      ];

      function validFileType(file) {
        return fileTypes.includes(file.type);
      }

      function returnFileSize(number) {
        if (number < 1024) {
          return number + 'bytes';
        } else if(number >= 1024 && number < 1048576) {
          return (number/1024).toFixed(1) + 'KB';
        } else if(number >= 1048576) {
          return (number/1048576).toFixed(1) + 'MB';
        }
      }
    }

    async function uploadFile() {
      const resource = `${dataUploadURL.getAttribute('data-url')}upload.php`
      let formData = new FormData(); 

      function handleFiles() {
        if (inputFiles.length > 0) {
          const inpFiles = inputFiles.filter(file => {
            return file.files[0]
          })
          return inpFiles
        }
      }
      const files = handleFiles().length > 0 ? handleFiles() : 0
      for (let i=0; i<files.length; i++) {
        formData.append("file", files[i].files[0]);
        
        await fetch(resource, {
          method: "POST", 
          body: formData
        })
      }
    }


    async function handleOrderSave(e) {
      if (!clicked) {
        clicked = true
        let radioIds = []
        let radioMetas = []
        let radioValues = []
        let uploadIds = []
        let uploadMetas = []
        let uploadValues = []
        let statIds = []
        let statMetas = []
        let statValues = []
        let noteIds = []
        let noteMetas = []
        let noteValues = []
        
        for (let i=0; i < listItems.length; i++) {
          const radios = listItems[i].querySelectorAll('input[type="radio"]')
          const statMeta = listItems[i].querySelector('[data-meta-stat]')
          const statArrays = []
          const statValueCounts = {}

          for (let x=0; x < radios.length; x++) {
            if (radios[x].checked) {
              const id = radios[x].closest('[data-meta-id]').getAttribute('data-meta-id')
              const meta = radios[x].getAttribute('data-meta-option')
              const value = radios[x].value

              radioIds.push(id)
              radioMetas.push(meta)
              radioValues.push(value)
              
              statArrays.push(value)
              if (!statIds.includes(id)) { 
                statIds.push(id) 
              }
            }
          }

          function handleChecklistStatus() {
            const arrLength = statArrays.length

            statArrays.forEach(item => {
              if (statValueCounts[item]) {
                statValueCounts[item] += 1
                return
              }
              statValueCounts[item] = 1
            })

            if (statValueCounts.issue !== undefined && statValueCounts.issue > 0) {
              return 'issue'
            }
            if (statValueCounts.ready !== undefined && statValueCounts.ready === arrLength) {
              return 'ready'
            }
            if (statValueCounts.pending !== undefined && statValueCounts.pending === arrLength) {
              return 'pending'
            }
            return 'pending'
          }
          
          statMetas.push(statMeta.getAttribute('data-meta-stat'))
          statValues.push(handleChecklistStatus())
        }


        if (inputFiles.length > 0) {
          const filteredIFiles = inputFiles.filter(file => {
            if (file.files.length) {
              return file
            }
          })

          const fIFiles = filteredIFiles.length > 0 ? filteredIFiles : 0
          for (let i = 0; i < fIFiles.length; i++) {
            const imgs = fIFiles[i].files

            if (imgs.length !== 0) {
              for (const img of imgs) {
                const id = fIFiles[i].closest('[data-meta-id]').getAttribute('data-meta-id')
                const meta = fIFiles[i].closest('[data-meta-upload]').getAttribute('data-meta-upload')
                const value = img.name
                uploadIds.push(id)
                uploadMetas.push(meta)
                uploadValues.push(value)
              }
            }
          }
        }

        if (notes.length > 0) {
          const filteredNotes = notes.filter(note => {
            if (note.value !== '') {
              return note
            }
          })

          const fNotes = filteredNotes.length > 0 ? filteredNotes : 0
          for (let i = 0; i < fNotes.length; i++) {
            const id = fNotes[i].closest('[data-meta-id]').getAttribute('data-meta-id')
            const meta = fNotes[i].getAttribute('data-meta-note')
            const value = fNotes[i].value
            noteIds.push(id)
            noteMetas.push(meta)
            noteValues.push(value)
          }
        }

        // object that holds the array values
        const metaField = {
          radids: [],
          radmetas: [],
          radvalues: [],
          uplids: [],
          uplmetas: [],
          uplvalues: [],
          statids: [],
          statmetas: [],
          statvalues: [],
          noteids: [],
          notemetas: [],
          notevalues: [],
        }
        
        metaField.radids = radioIds
        metaField.radmetas = radioMetas
        metaField.radvalues = radioValues
        metaField.uplids = uploadIds
        metaField.uplmetas = uploadMetas
        metaField.uplvalues = uploadValues
        metaField.statids = statIds
        metaField.statmetas = statMetas
        metaField.statvalues = statValues
        metaField.noteids = noteIds
        metaField.notemetas = noteMetas
        metaField.notevalues = noteValues

        // console.log(metaField.radids)
        // console.log(metaField.radmetas)
        // console.log(metaField.radvalues)

        // console.log(metaField.uplids)
        // console.log(metaField.uplmetas)
        // console.log(metaField.uplvalues)

        // console.log(metaField.statids)
        // console.log(metaField.statmetas)
        // console.log(metaField.statvalues)

        // console.log(metaField.noteids)
        // console.log(metaField.notemetas)
        // console.log(metaField.notevalues)

        let formData = new FormData()

        // add array values to form data
        formData.append('action', 'my_ajax_shared_post')
        formData.append('nonce', thspadminorder.ajaxnonceA)
        formData.append('order_id', orderID.getAttribute('data-order-id'))
        formData.append('radids', JSON.stringify(metaField.radids))
        formData.append('radmetas', JSON.stringify(metaField.radmetas))
        formData.append('radvalues', JSON.stringify(metaField.radvalues))
        formData.append('uplids', JSON.stringify(metaField.uplids))
        formData.append('uplmetas', JSON.stringify(metaField.uplmetas))
        formData.append('uplvalues', JSON.stringify(metaField.uplvalues))
        formData.append('statids', JSON.stringify(metaField.statids))
        formData.append('statmetas', JSON.stringify(metaField.statmetas))
        formData.append('statvalues', JSON.stringify(metaField.statvalues))
        formData.append('noteids', JSON.stringify(metaField.noteids))
        formData.append('notemetas', JSON.stringify(metaField.notemetas))
        formData.append('notevalues', JSON.stringify(metaField.notevalues))

        await fetch(thspadminorder.ajaxurlA, {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(jsonResponse => console.log(jsonResponse))
        .catch(err => console.log('promised error: ', err))
        
        // $.ajax({
        //   url: thspadminorder.ajaxurl,
        //   type: 'POST',
        //   data: {
        //     action: 'my_ajax_shared_post',
        //     order_id: orderID.getAttribute('data-order-id'),

        //     radids: metaField.radids,
        //     radmetas: metaField.radmetas,
        //     radvalues: metaField.radvalues,

        //     uplids: metaField.uplids,
        //     uplmetas: metaField.uplmetas,
        //     uplvalues: metaField.uplvalues,
            
        //     statids: metaField.statids,
        //     statmetas: metaField.statmetas,
        //     statvalues: metaField.statvalues,

        //     noteids: metaField.noteids,
        //     notemetas: metaField.notemetas,
        //     notevalues: metaField.notevalues,
        //   },
        //   beforeSend: function(html) {
        //     // console.log(html)
        //   },
        //   success: function( html ) {
        //     console.log(html)
        //   }
        // });
      }
    }


    const checklists = metaBox.querySelectorAll('.checklist_meta_box')
    checklists.forEach(checklist => {
      const lists = checklist.querySelectorAll('input[type="radio"]')
      
      for (let i=0; i<lists.length; i++) {
        lists[i].addEventListener('click', handleChecklistClick)
          
        function handleChecklistClick(e) {
          
          lists.forEach(list => list.closest('li').classList.remove('selected'))
          e.target.closest('li').classList.add('selected')

          if (this.value === 'issue') {
            this.closest('.checklist_option').nextElementSibling.style.display = 'flex'
          } else {
            this.closest('.checklist_option').nextElementSibling.style.display = 'none'
          }

        }
      }
    })


    const reviewBtns = metaBox.querySelectorAll('.review_btn')
    reviewBtns.forEach(btn => {
      btn.addEventListener('click', handleReviewAccordionClick)
      
      function handleReviewAccordionClick(e) {
        e.preventDefault()
        e.target.classList.toggle('activ')
        
        const box = e.target.closest('.list_items').querySelector('.r_checklist_meta_box')
        if (box.style.display === 'flex') {
          box.style.display = 'none';
        } else {
          box.style.display = 'flex';
        }
      }
    })

    removeUploads.forEach(upload => {
      upload.addEventListener('click', handleRemoveUploadClick)

      function handleRemoveUploadClick() {
        this.parentElement.nextElementSibling.style.display = 'block'
        this.parentElement.style.display = 'none'
      }
    })
    

    if (modImgs.length > 0) {

      modImgs.forEach((img, i) => {
        const modal = img.closest('.modal')
        const caption = img.nextElementSibling
        const canvas = fileThumbs[i]
        const span = modal.querySelector(".close")

        handleCanvasDisplay(img, canvas)

        canvas.addEventListener('click', function(e) {
          modal.setAttribute('style', 'opacity:1;visibility:visible;pointer-events:all;')
          img.classList.add('fade_in')
          caption.classList.add('fade_in')
        })

        span.addEventListener('click', function(e) { 
          modal.setAttribute('style', 'opacity:0;visibility:hidden;pointer-events:none;')
          img.classList.remove('fade_in')
          caption.classList.remove('fade_in')
        })
      })
      
      function handleCanvasDisplay(img, canvas) {
        const ctx = canvas.getContext('2d');
        const image = new Image();
        image.addEventListener('load', drawImageActualSize)
        image.src = img.src
        
        function drawImageActualSize() {
          const width = this.width
          const height = this.height
          canvas.width = width
          canvas.height = height
          canvas.setAttribute('title', `${img.alt}`)
          ctx.drawImage(this, 0, 0, width, height);
        }
      }
    }
  }
  
})(jQuery);
