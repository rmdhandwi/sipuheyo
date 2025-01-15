export default class Helper {
   //static apiUrl = "http://127.0.0.1:8000/api";
   static apiUrl = "/api";

   static getResepKode(id) {
      var str = "" + 1
      var pad = "000000"
      var ans = pad.substring(0, pad.length - str.length) + str;
      return "RS" + ans;
   }

   static getKode = (id, obj) => {
      var str = "" + id;
      var pad = "000000";
      var ans = pad.substring(0, pad.length - str.length) + str
      switch (obj.toLowerCase()) {
         case 'obat':
            return "OB" + ans;
         case 'dokter':
            return "DT" + ans;
         case 'pasien':
            return "PS" + ans;
         case 'penyakit':
            return "PK" + ans;
         case 'rekammedik':
            return "RM" + ans;
         case 'pegawai':
            return "PG" + ans;
         default:
            return "";
      }

   }


   static getPadNumber = (id) => {
      var str = "" + id;
      var pad = "00";
      return pad.substring(0, pad.length - str.length) + str
   }

   static getXPadNumber = (num, dig) => {
      var str = "" + num;
      var pad =str.length < dig ? "0".repeat(dig-str.length)+str:str;
      return pad
   }

   static getOnlyDate(date) {
      const year = date.getFullYear(); // Gets the year (e.g., 2024)
      const month = this.getXPadNumber(date.getMonth() + 1); // Gets the month (0-11, so we add 1 to get 1-12)
      const day =this.getXPadNumber(date.getDate(),2); // Gets the day of the month (1-31)
     return `${year}-${month}-${day}`;
   }

}