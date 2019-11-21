function setItemInStorage(dataKey, data) {
    localStorage.setItem(dataKey, JSON.stringify(data));
}

function removeItemStorage(dataKey) {
    localStorage.removeItem(dataKey);
}

function getItemFromStorage(dataKey) {
    const data = localStorage.getItem(dataKey);
    return data ? JSON.parse(data) : null;
}

export const StorageComponent = {
    setItemInStorage,
    removeItemStorage,
    getItemFromStorage
};
