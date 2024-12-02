import Tesseract from "tesseract.js"

(async () => {
    const worker = await Tesseract.createWorker('chi_tra')
    const shot = document.getElementById('shot')
    const canvas = document.getElementById('overlay')
    const memberFound = document.getElementById('members')
    const ctx = canvas.getContext('2d')

    const isNumber = (value) => typeof value === 'number' && isFinite(value)

    const members = ['藥吹', '藥炎', '藥夯', '藥號', 'Rushh', '藥夯', '藥奶']

    /**
     * @param {File} file to scan name
     */
    const scanName = async function (file) {
        const res = await worker.recognize(file)

        shot.src = file

        await new Promise((resolve, reject) => {
            shot.onload = () => resolve()
            shot.onerror = (err) => reject(err)
        })

        canvas.width = shot.width
        canvas.height = shot.height

        // Clear previous drawings
        ctx.clearRect(0, 0, canvas.width, canvas.height)

        let possibleLv = Array(), bbLvStart = null, bbLvEnd = null, name = '', skip = false
        // Draw bounding boxes around recognized words
        res.data.words.forEach((word) => {

            let n = Number(word.text)
            if (isNumber(n) && n > 10) {
                console.log(word)
                // possible lv number
                possibleLv.push(word)
                return
            }

            if (word.text.includes('等')) {
                drawBB(word)
            }


            if (word.text.includes('級')) {
                // found end of level line, stop skipping
                skip = false
                drawBB(word)
                bbLvEnd = word
                possibleLv.some((i) => {
                    if (isSameLine(bbLvEnd, i)) {
                        // it's the lv number, so it's start of level line.
                        bbLvStart = i
                        drawBB(i)
                        return true
                    }

                    return false
                })

                return
            }

            if (bbLvStart !== null && bbLvEnd !== null && !skip) {
                // bounding box of level line
                let x0 = bbLvStart.bbox.x0
                let y0 = Math.min(bbLvStart.bbox.y0, bbLvEnd.bbox.y0)
                let y1 = Math.max(bbLvStart.bbox.y1, bbLvEnd.bbox.y1)
                let h = y1 - y0
                let xTolerance = (bbLvEnd.bbox.x1 - bbLvEnd.bbox.x0) / 2
                let yTolerance = (bbLvEnd.bbox.y1 - bbLvEnd.bbox.y0) / 2

                // name line in following range:
                //   1. top lower than level line (y0)
                //   2. bottom higher than 2x height of level line (y1, with tolerance)
                //   3. left aligned (x0, with tolerance)
                let isName = word.bbox.y0 > y0 && word.bbox.y1 < (y1 + 2 * h + yTolerance) && word.bbox.x0 > x0 - xTolerance
                if (isName) {
                    name = name + word.text.trim()
                    drawBB(word, 2)
                    console.log(name)
                    if (members.includes(name)) {
                        const newParagraph = document.createElement('p')
                        newParagraph.textContent = name
                        memberFound.appendChild(newParagraph)

                        // found member, reset name and skip to until next bounding
                        name = ''
                        skip = true
                    }
                }
            }
        })
    }

    /**
     * @param {Word} sub
     * @param {Word} chk
     *
     * check if chk is same line as subject(sub)
     */
    const isSameLine = (sub, chk) => {
        let tolerance = (sub.bbox.y1 - sub.bbox.y0) / 2

        return chk.bbox.y1 < (sub.bbox.y1 + tolerance) && chk.bbox.y0 > (sub.bbox.y0 - tolerance)
    }

    /**
     * @param {Word} word
     */
    const drawBB = (word, pos = 1) => {
        const {x0, y0, x1, y1} = word.bbox // Bounding box coordinates
        ctx.strokeStyle = 'red'
        ctx.lineWidth = 2
        ctx.strokeRect(x0, y0, x1 - x0, y1 - y0)
        ctx.font = "14px Arial"
        ctx.fillStyle = "red"
        switch (pos) {
            case 1: // top
                ctx.fillText(word.text, x0, y0 - 5)
                break
            case 2: // bottom
                ctx.fillText(word.text, x0, y1 + 22 - 5)
                break
        }
    }

// for debug
    await scanName(document.getElementById('sample').src)

    document.addEventListener('paste', async (e) => {
        e.preventDefault()

        for (const item of e.clipboardData.files) {
            if (item.type.startsWith('image/')) {
                console.log(item)

                shot.src = URL.createObjectURL(item)
                await scanName(shot.src)
            }
        }
    })
})()
