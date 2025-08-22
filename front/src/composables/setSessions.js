/**
 *
 * @param session string com o nome da localStorage
 */
export function setSessions(session) {
    let data = null;

    if (session === 'Users') {
        /**
         *
         * @param url string com a url destino no backend
         * @param params objeto com os campos do filtro, devem ter a mesma chave dos dados vindos do backend
         */
        data = {
            url: 'users',
            title: 'Usuário',
            pluralTitle: 'Usuários',
            params: {
                page: 1,
                order_by: 'id',
                order: 'asc',
                limit: 25,
                email: '',
                name: '',
                position: '',
                type: '',
                active: 1,
                global: '',
            }
        }

    }

    let areKeysEqual = false;
    const obj = JSON.parse(localStorage.getItem(session));
    if (obj) {
        const objKeys = Object.keys(obj.params).sort();
        const userKeys = Object.keys(data.params).sort();

        areKeysEqual = JSON.stringify(objKeys) !== JSON.stringify(userKeys);
    }

    if (!obj || areKeysEqual) {
        localStorage.setItem(session, JSON.stringify(data));
    }
}
