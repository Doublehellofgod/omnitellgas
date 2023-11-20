const bodyparser = require('body-parser')
const mailer = require('./nodemailer')
var express = require('express');
//const nademailer = require("nodemailer");

const app = express()

const PORT = 3004
app.use(bodyparser.urlencoded({extended: false}))
var urlencodedParser = bodyparser.urlencoded({ extended: false })

app.get('/email', urlencodedParser, function (req, res) {

    //console.log(req.body)
    //if(!req.body.email || !req.body.pass)
    //console.log('HHHHHHHHHHHHHHHHHHHHHH')
    console.log('name = ',req.query.name);
    const message = {
        //charset:'utf-8',
        from:'oktell@tech.omnitell.ru',
        to: ' khusainov@spg-trade.com',
        subject: req.query.name,
        text: `Организация: ${req.query.org} \nТелефон: ${req.query.sel} \n Сообщение:\n${req.query.text} `
}

    mailer(message);
    res.send('Отправленно')
})
//Главная страница
app.get('/', (req, res)=>{
    res.sendFile(__dirname + '/index.html')
})
app.get('/main.js', (req, res) => {
    res.sendFile(__dirname + '/main.js')
})
///////////////////
//Подключение к разным контролерам
/*app.get('/krioaz', (req, res)=>{
    res.sendFile(__dirname + '/app/krioaz/krioaz.html')
})
app.get('/faq', (req, res)=>{
    res.sendFile(__dirname + '/app/faq/faq.html')
})
app.get('/appeal', (req, res)=>{
    res.sendFile(__dirname + '/app/appeal/appeal.html')
})

app.get('/krioaz.js', (req, res) => {

    res.sendFile(__dirname + '/app/krioaz/krioaz.js')
})
app.get('/appeal.js', (req, res) => {
    //console.log(req.body)
    //if(!req.body.email || !req.body.pass)
    res.sendFile(__dirname + '/app/appeal/appeal.js')
})
*/

//Разные варианты имен эксель файла
app.get('/assets/file/name.xlsx', (req, res) => {
    console.log('1')
    res.sendFile(__dirname + '/assets/file/Перечень_действующих_КРИОАЗС.xlsx')
})
app.get(`/assets/file/${encodeURI('Перечень_действующих_КРИОАЗС.xlsx')}`, (req, res) => {
    console.log('2')
    res.sendFile(__dirname + '/assets/file/Перечень_действующих_КРИОАЗС.xlsx')
})
app.get('/assets/file/Перечень_действующих_КРИОАЗС.xlsx', (req, res) => {
    console.log('3')
    res.sendFile(__dirname + '/assets/file/Перечень_действующих_КРИОАЗС.xlsx')
})
//////////
app.listen(PORT,()=> console.log(`Server start on http://localhost:${PORT} ${__dirname}`))

app.get('/assets/styles/bootstrap.css', (req, res) => {
    res.sendFile(__dirname + '/assets/styles/bootstrap.css')
})
app.get('/jQuery.js', (req, res) => {
    res.sendFile(__dirname + '/jQuery.js')
})
