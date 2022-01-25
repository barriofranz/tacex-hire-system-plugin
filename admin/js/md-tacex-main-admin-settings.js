(function($) {
  const thspPage = document.querySelector('.thsp_page')

  if (thspPage !== null) {
    const infoForm = thspPage.querySelector('#info_form')
    const saveBtn = thspPage.querySelector('#save_info')
    const title = infoForm.querySelector('[name="title"]')
    const orderNum = infoForm.querySelector('[name="order_num"]')
    const description = infoForm.querySelector('[name="description"]')
    const action = infoForm.querySelector('[name="action"]')
    const formError = infoForm.querySelector('#form_error')
    let clicked = false

    saveBtn.addEventListener('click', handleSettingsSave)

    async function handleSettingsSave(e) {
      if (!clicked) {
        clicked = true
        e.preventDefault()

        let formData = new FormData()
        formError.textContent = ''
        if (title.value !== '' && description !== null) {
          formData.append('order_num', `${orderNum.value}`)
          formData.append('title', `${title.value}`)
          formData.append('description', `${description.value}`)
          formData.append('action', `${action.value}`)
          formError.textContent = ''
        } else {
          formError.textContent = 'Title and description fields should have values.'
        }

        await fetch(thspadminsettings.ajaxurlB, {
          method: 'POST',
          body: formData
        }).then(response => {
          return response.json();
        }).then(jsonResponse => {
          title.value = ''
          description.value = ''
          orderNum.value = ''
          description.removeAttribute('style')
          clicked = false
          const data = jsonResponse.data.data
          const response = jsonResponse.data.response
          const infoArticle = document.querySelector('#r_info_articles')
          
          if ((data.title === response.title && data.order_num === response.order_num && data.descriptions === response.description) && infoArticle !== null) {

            if (infoArticle.children[0].tagName === 'H4') {
              infoArticle.children[0].remove()
            }
            
            const articleTemplate = `<div class="card info_card z-depth-0" data-order="${data.order_num}"><div class="card-content"><span class="card-title">${data.title}</span><p style="white-space: pre-line">${data.descriptions}</p></div><div class="card-action right-align"><a href="#" class="btn brand z-depth-0 id_to_delete" data-value="${data.id}"><i data-feather="trash-2"></i></a></div>`
            
            infoArticle.innerHTML += articleTemplate
          }
          
        }).catch(err => {
          console.log('promised error: ', err)
        })
      }
    }

    const parent = thspPage.querySelector('#r_info_articles')
    if (idtoDelete !== null || idtoDelete !== undefined) {
      handleMutationObserverDeleteID(idtoDelete, parent)
    }

    function handleMutationObserverDeleteID(ids, info) {
      if (ids.length > 0) {

        ids.forEach(id => {
          id.addEventListener('click', handleDeleteIDClick)

          async function handleDeleteIDClick(e) {
            if (!stat) {
              e.preventDefault()
              stat = true

              let formData = new FormData()
              const articleID = e.target.getAttribute('data-value')
              formData.append('action', 'delete_info_ajax')
              formData.append('id', `${articleID}`)

              await fetch(thspadminsettings.ajaxurlB, {
                method: 'POST',
                body: formData
              }).then(response => {
                return response.json();
              }).then(jsonResponse => {
                if (jsonResponse.success) {
                  console.log(jsonResponse)
                  if (jsonResponse.data.id == articleID) {
                    id.closest('.card').remove()
                    stat = false

                    if (info.children.length === 0) {
                      const noArticles = `<h4 class="center-align">No available information article. Please add your article.</h4>`
                      info.innerHTML = noArticles
                    }
                  }
                }
              }).catch(err => {
                console.log('promised error: ', err)
              })
            }
          }
        })
      }
    }

    // handle entry change in checklist information tab
    const mutationObserver = new MutationObserver(entries => {
      console.log(entries)
      feather.replace()

      const idtoDelete = document.querySelectorAll('.id_to_delete')
      handleMutationObserverDeleteID(idtoDelete, parent)
    })
    
    mutationObserver.observe(parent, { childList: true })
  }

})(jQuery);
