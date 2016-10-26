
//creates matrix object
function Matrix(ary) {
    this.mtx = ary
    this.height = ary.length;
    this.width = ary[0].length;
}

//convert matrix to string
Matrix.prototype.toString = function() {
    var s = []
    for (var i = 0; i < this.mtx.length; i++)
        s.push( this.mtx[i].join(",") );
    return s.join("\n");
}

// returns a new matrix
Matrix.prototype.transpose = function() {
    var transposed = [];
    for (var i = 0; i < this.width; i++) {
        transposed[i] = [];
        for (var j = 0; j < this.height; j++) {
            transposed[i][j] = this.mtx[j][i];
        }
    }
    return new Matrix(transposed);
}

//performs rref on given matrix
Matrix.prototype.toReducedRowEchelonForm = function() {
    var lead = 0;
    for (var r = 0; r < this.height; r++) {
        if (this.width <= lead) {
            return;
        }
        var i = r;
        while (this.mtx[i][lead] == 0) {
            i++;
            if (this.height == i) {
                i = r;
                lead++;
                if (this.width == lead) {
                    return;
                }
            }
        }

        var tmp = this.mtx[i];
        this.mtx[i] = this.mtx[r];
        this.mtx[r] = tmp;

        var val = this.mtx[r][lead];
        for (var j = 0; j < this.width; j++) {
            this.mtx[r][j] /= val;
        }

        for (var i = 0; i < this.height; i++) {
            if (i == r) continue;
            val = this.mtx[i][lead];
            for (var j = 0; j < this.width; j++) {
                this.mtx[i][j] -= val * this.mtx[r][j];
            }
        }
        lead++;
    }
    return this;
}

var m = new Matrix([
  [ 50, 1, 1, 45],
  [ 0, 30, 2, 35],
  [ 2, 0, 10, 20]
]);
console.log(m.toReducedRowEchelonForm());
console.log();

//https://rosettacode.org/wiki/Matrix_transposition#JavaScript
//https://rosettacode.org/wiki/Reduced_row_echelon_form#JavaScript
