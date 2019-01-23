export default class Cliente {
    constructor(RUC, nombre, correo, telefono, tipoPrecio) {
      this.RUC = RUC;
      this.nombre = nombre;
      this.correo = correo;
      this.telefono = telefono;
      this.tipoPrecio = tipoPrecio;
    }

    getTipoPrecio() {
        return this.tipoPrecio;
    }
  }