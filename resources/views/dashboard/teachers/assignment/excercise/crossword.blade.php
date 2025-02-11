<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cross Word</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />

    <style>
        #header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 1em;
            flex-wrap: wrap;
        }

        .div-remove {
            position: absolute;
            cursor: pointer;
            right: 10px;
            color: red;
            transition: all 1s;
        }

        .div-remove i:hover {
            transform: scale(1.1);
        }

        body {
            background-image: url({{ asset('excercise/assets/images/themes/theme2.png') }});
            background-size: cover;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .button {
            backface-visibility: hidden;
            position: relative;
            cursor: pointer;
            display: inline-block;
            white-space: nowrap;
            background: #19399b;
            border-radius: 100px;
            border: 0.5px solid rgba(0, 0, 0, 0.2);
            border-width: 0.5px 0.5px 0.5px 0.5px;
            padding: 4px 22px 5px 22px;
            box-shadow: 0px 1px 1px rgba(0, 0, 0, 0.6), 0px 0px 5px rgba(255, 255, 255, 1), 0px 1px 0px 3px rgba(0, 0, 0, 0.2), inset 0px 1px 0px rgba(255, 255, 255, 1);
            color: #ffffff;
            font-size: 16px;
            font-family: Comic Sans MS;
            font-weight: 900;
            font-style: normal
        }

        .button>div {
            color: #999;
            font-size: 10px;
            font-family: Helvetica Neue;
            font-weight: initial;
            font-style: normal;
            text-align: center;
            margin: 0px 0px 0px 0px
        }

        .button>i {
            font-size: 1em;
            border-radius: 0px;
            border: 0px solid transparent;
            border-width: 0px 0px 0px 0px;
            padding: 0px 0px 0px 0px;
            margin: 0px 0px 0px 0px;
            position: static
        }

        .button>.ld {
            font-size: initial
        }

        .button:hover {
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <style>
        .container-loader {
            position: absolute;
            height: 100vh;
            width: 100vw;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: white;
            flex-direction: column;
        }

        .loader {
            display: inline-block;
            width: 60px;
            height: 60px;
            border: 6px solid #3498db;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="container-loader">
        <div class="loader"></div>
        <p>loading Please Wait ...</p>
    </div>

    <script>
        function showLoader(show = true) {
            document.querySelector('.container-loader').style.display = show ? 'flex' : 'none';
        }
        // Add a delay to simulate loading
        setTimeout(function() {
            showLoader(false)
        }, 1000);
    </script>
    <div class="container">
        <div id="header" class="d-flex justify-content-center">
            <h1 class="jumbotron text-primary">Create Cross Words Game</h1>
        </div>
        <!-- Added  -->
        <div class="d-flex align-items justify-content-center flex-wrap" id="list">

        </div>

        <!-- Add new  -->
        <div class="modal-content bg-white p-5">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Add new Clue</h1>
            </div>
            <div class="modal-body">


                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Word (Max 10 letter)*</label>
                    <input type="text" maxlength="10" class="form-control" id="word"
                        aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Clue Type*</label>
                    <select name="type" id="hintType" class="form-select" aria-label="Default select example">
                        <option value="text">Text</option>
                        <option value="image">Image</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label" id="clue">Clue *</label>
                    <div>
                        <textarea class="form-control" placeholder="Enter a Clue" id="hint"></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" id="saveWord">Add Clue</button>
            </div>
        </div>
        <!-- Footer -->
        <div class="d-flex justify-content-center w100 mt-5 mb-5">
            <div class="button" onclick="previewOutput()">
                Preview
                <div></div>
            </div>
        </div>
        <div>



            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Create</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">


                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Word (Max 10 letter)*</label>
                                <input type="text" maxlength="10" class="form-control" id="word"
                                    aria-describedby="emailHelp">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Clue Type*</label>
                                <select name="type" id="hintType" class="form-select"
                                    aria-label="Default select example">
                                    <option value="text">Text</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label" id="clue">Clue *</label>
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Enter a Clue" id="hint"></textarea>
                                </div>
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                id="saveWord">Save
                                changes</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                <div id="toast" class="toast bg-primary" role="alert" aria-live="assertive"
                    aria-atomic="true">
                    <div class="toast-body text-white"></div>
                </div>
            </div>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
            </script>
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>

            <script>
                const saveWord = document.getElementById('saveWord');
                const hintType = document.getElementById('hintType');
                const hint = document.getElementById('hint');

                var wordsJson = [];

                hintType.addEventListener('change', function(e) {
                    const clue = document.getElementById('clue');
                    if (e.target.value === 'text') {
                        clue.innerText = "Clue Text *";
                    } else {
                        clue.innerText = "Clue Image Link *";
                    }
                })

                saveWord.addEventListener('click', function() {
                    const word = document.getElementById('word').value;
                    const hintType = document.getElementById('hintType').value;
                    const hint = document.getElementById('hint').value;
                    if (wordsJson.length > 0 && !checkCharacterMatch(wordsJson[wordsJson.length - 1].word, word)) {
                        showToast('Entered Word Doest Match Previous Word', 'bg-info')
                        return;
                    }
                    if (word.length == 0) {
                        showToast('Please Enter Word', 'bg-danger')
                        return;
                    }
                    if (hint.length == 0) {
                        showToast('Please Enter The Hint', 'bg-danger')
                        return;
                    }
                    wordsJson.push({
                        word,
                        hintType,
                        hint
                    })

                    document.getElementById('word').value = ''
                    document.getElementById('hint').value = ''
                    renderWords();
                    showToast('Added New Word', 'bg-success')
                })

                function checkCharacterMatch(str1, str2) {
                    for (let char of str1) {
                        if (str2.includes(char)) {
                            return true;
                        }
                    }
                    return false;
                }

                function renderWords() {
                    const list = document.getElementById('list');
                    list.innerHTML = '';
                    for (let i = 0; i < wordsJson.length; i++) {
                        const data = wordsJson[i];

                        list.innerHTML += `
                        <div class="card m-2 bg-image hover-zoom p-2" style="width: 100%;">
                            <h4>Word : ${data.word}</h4>
                            <p>Type : ${data.hintType}</p>
                            ${data.hintType === 'image' ? `<p>hint : <img src="${data.hint}" width="100px" height="100px" /></p>` : `<p>hint : ${data.hint}</p>`}
                            
                            <div class="div-remove"  onclick="deleteWord(${i})" >
                                <i class="fa-solid fa-trash" style='font-size:1.4rem' ></i>
                            </div>

                        </div>
                        
                        `;

                    }

                }

                function showToast(message, color) {
                    const toastElement = document.getElementById('toast');
                    const toastBodyElement = toastElement.querySelector('.toast-body');

                    // Update the toast message
                    toastBodyElement.innerText = message;

                    // Change the toast color
                    toastElement.classList.remove('bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info');
                    toastElement.classList.add(color);

                    // Show the toast
                    const toast = new bootstrap.Toast(toastElement);
                    toast.show();

                    // Hide the toast after 3 seconds
                    setTimeout(function() {
                        toast.hide();
                    }, 3000);
                }



                function deleteWord(index) {
                    wordsJson.splice(index, 1);
                    renderWords()
                    showToast('Removed', 'bg-danger');
                }

                async function previewOutput() {
                    if (wordsJson.length >= 2) {

                        const sendJSON = JSON.stringify({
                            type: 'crossword',
                            'wordsjson': JSON.stringify(wordsJson)
                        })
                        console.log(sendJSON)
                        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        var headers = {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        };

                        const form = new FormData();
                        form.append('data', sendJSON)
                        form.append('type', 'crossword')

                        const req = await fetch(window.location.href, {
                            method: 'POST',
                            body: JSON.stringify({
                                data: sendJSON,
                                type: 'crossword',
                            }),
                            headers: headers,
                        })
                        showLoader()
                        const res = await req.json()

                        if (res.success) {
                            showToast('created to new Excercise', 'bg-success')

                            showLoader(false)
                            var link = document.createElement("a"); // Or maybe get it from the current document
                            link.href = '../../../api/assignments/' + res.id;
                            link.target = '_blank'
                            document.body.appendChild(link);
                            link.click();
                        }
                       
                    } else {
                        showToast('Please Add Atleast Two Clue', 'bg-danger')
                    }

                }
            </script>
</body>

</html>
