const getTime = () => {
    let now = new Date();

    let year = formatNumber(now.getFullYear());
    let month = formatNumber(now.getMonth());
    let day = formatNumber(now.getDate());
    let hours = formatNumber(now.getHours());
    let minutes = formatNumber(now.getMinutes());
    let seconds = formatNumber(now.getSeconds());

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
};

const formatNumber = (number) => {
    return number < 10 ? '0' + number : number;
};